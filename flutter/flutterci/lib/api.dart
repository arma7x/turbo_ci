import 'dart:io';
import 'dart:core';
import 'package:http/http.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Api {
  static const String BASE_URL = 'turboci.herokuapp.com';

  static const String AUTH_WHOAMI = '/api/authentication/whoami';
  static const String AUTH_LOGIN = '/api/authentication/login';
  static const String AUTH_REGISTER = '/api/authentication/register';
  static const String AUTH_FORGOT_PASSWORD = '/api/authentication/forgot_password';
  static const String AUTH_LOG_OUT = '/api/authentication/log_out';

  static void setAuthToken(String token) async {
    if (token != null) {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', token);
    }
  }

  static getAuthToken() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  static String getURLPath(String path) {
    if (path.startsWith('http')) {
      return path;
    }
    return Uri.https(BASE_URL, path).toString();
  }

  // AUTH
  static Future postWhoAmI() {
    final url = Uri.https(BASE_URL, AUTH_WHOAMI);
    final httpClient = HttpClient();
    return httpClient.postUrl(url);
  }

  // AUTH -> email, password, fcm
  static MultipartRequest postLogin() {
    final url = Uri.https(BASE_URL, AUTH_LOGIN);
    return new MultipartRequest("POST", url);
  }

  // AUTH -> username, email, password, confirm_password
  static MultipartRequest postRegister() {
    final url = Uri.https(BASE_URL, AUTH_REGISTER);
    return MultipartRequest("POST", url);
  }

  // AUTH -> email
  static MultipartRequest postForgotPassword() {
    final url = Uri.https(BASE_URL, AUTH_FORGOT_PASSWORD);
    return MultipartRequest("POST", url);
  }

  // AUTH
  static Future postLogout() {
    final url = Uri.https(BASE_URL, AUTH_LOG_OUT);
    final httpClient = HttpClient();
    return httpClient.postUrl(url);
  }
}
