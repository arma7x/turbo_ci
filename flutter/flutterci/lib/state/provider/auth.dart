import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutterci/config.dart';
import 'package:flutterci/api.dart';

import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:fluttertoast/fluttertoast.dart';

class Auth with ChangeNotifier {
  Auth() {
    whoAmI();
  }

  Future<SharedPreferences> _prefs = SharedPreferences.getInstance();

  String _id = "Guest";
  String get id => _id;

  String _username = "Guest";
  String get username => _username;

  String _email = "Please login";
  String get email => _email;

  ImageProvider _avatar = CachedNetworkImageProvider(Config.APP_ICON);
  ImageProvider get avatar => _avatar;

  bool _loggedIn = false;
  bool get loggedIn => _loggedIn;

  void updateAuthMetadata(id, username, email, avatar, loggedIn) {
    _id = id;
    _username = username;
    _email = email;
    _avatar = avatar;
    _loggedIn = loggedIn;
    notifyListeners();
  }

  void whoAmI() async {
    final SharedPreferences prefs = await _prefs;
    final _jwttoken = await Api.getAuthToken();
    Map<String, dynamic> temp = {};
    try {
      if (_jwttoken != null) {
        Map<String, dynamic> jwt = parseJwt(_jwttoken);
        if (jwt["jti"] != null) {
          if (jwt["jti"].split('.').length == 3) {
            temp = json.decode(prefs.getString('whoami_data'));
            //Image.memory(base64Decode(temp["avatar"].split(",")[1]))
            updateAuthMetadata(temp["id"], temp["username"], temp["email"],
                CachedNetworkImageProvider(Config.APP_ICON), true);
          }
        } else {
          await prefs.remove('whoami_data');
          updateAuthMetadata("Guest", "Guest", "Please login",
              CachedNetworkImageProvider(Config.APP_ICON), false);
        }
      }

      final request = await Api.postWhoAmI();
      request.headers.set('authorization', _jwttoken);
      final response = await request.close();
      Api.setAuthToken(response.headers.value('authorization'));
      if (response.statusCode == 200) {
        final responseBody = await response.transform(utf8.decoder).join();
        await prefs.setString('whoami_data', responseBody);
        temp = json.decode(responseBody);
        // Image.memory(base64Decode(temp["avatar"].split(",")[1]))
        updateAuthMetadata(temp["id"], temp["username"], temp["email"],
            CachedNetworkImageProvider(Config.APP_ICON), true);
      } else {
        await prefs.remove('whoami_data');
        updateAuthMetadata("Guest", "Guest", "Please login",
            CachedNetworkImageProvider(Config.APP_ICON), false);
      }
    } on Exception {
      // network error
    }
    notifyListeners();
  }

  void signOut(Function loadingCb) async {
    final SharedPreferences prefs = await _prefs;
    final _jwttoken = await Api.getAuthToken();
    Map<String, dynamic> responseBody = {};
    loadingCb(true);
    try {
      final request = await Api.postLogout();
      request.headers.set('authorization', _jwttoken);
      final response = await request.close();
      Api.setAuthToken(response.headers.value('authorization'));
      loadingCb(false);
      if (response.statusCode == 200) {
        await prefs.remove('whoami_data');
        updateAuthMetadata("Guest", "Guest", "Please login",
            CachedNetworkImageProvider(Config.APP_ICON), false);
        responseBody =
            json.decode(await response.transform(utf8.decoder).join());
        if (responseBody['message'] != null) {
          Fluttertoast.showToast(
              msg: responseBody['message'], toastLength: Toast.LENGTH_SHORT);
        }
      }
    } on Exception {
      loadingCb(false);
      // network error
    }
    notifyListeners();
  }

  Map<String, dynamic> parseJwt(String token) {
    final parts = token.split('.');
    if (parts.length != 3) {
      throw Exception('invalid token');
    }
    final payload = _decodeBase64(parts[1]);
    final payloadMap = json.decode(payload);
    if (payloadMap is! Map<String, dynamic>) {
      throw Exception('invalid payload');
    }
    return payloadMap;
  }

  String _decodeBase64(String str) {
    String output = str.replaceAll('-', '+').replaceAll('_', '/');
    switch (output.length % 4) {
      case 0:
        break;
      case 2:
        output += '==';
        break;
      case 3:
        output += '=';
        break;
      default:
        throw Exception('Illegal base64url string!"');
    }
    return utf8.decode(base64Url.decode(output));
  }
}
