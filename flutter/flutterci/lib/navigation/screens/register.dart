import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:flutterci/api.dart';

class RegisterPage extends StatefulWidget {
  RegisterPage({Key key, this.title, this.loadingCb}) : super(key: key);

  final String title;
  final Function loadingCb;

  @override
  _RegisterPageState createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  bool _loading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text(widget.title),
        ),
        body: IgnorePointer(
            ignoring: _loading,
            child: new Container(
                width: MediaQuery.of(context).size.width,
                height: MediaQuery.of(context).size.height,
                padding: EdgeInsets.fromLTRB(30.0, 0.0, 30.0, 0.0),
                alignment: Alignment.center,
                child: new Center(
                    child: new ListView(
                  shrinkWrap: true,
                  children: <Widget>[
                    _RegisterForm(loadingCb: widget.loadingCb)
                  ],
                )))));
  }
}

class _RegisterForm extends StatefulWidget {
  final Function loadingCb;

  _RegisterForm({Key key, this.loadingCb}) : super(key: key);

  @override
  State<StatefulWidget> createState() => _RegisterFormState();
}

class _RegisterFormState extends State<_RegisterForm> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController =
      TextEditingController();

  bool _secure = true;

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Container(
            alignment: Alignment.center,
            child: TextFormField(
              controller: _usernameController,
              decoration: InputDecoration(
                prefixIcon: Icon(Icons.person),
                labelText: 'Nama Pengguna',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Sila masukan nama pengguna';
                }
                return null;
              },
            ),
          ),
          Container(
            alignment: Alignment.center,
            child: TextFormField(
              controller: _emailController,
              decoration: InputDecoration(
                prefixIcon: Icon(Icons.email),
                labelText: 'Alamat E-mail',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Sila masukan alamat email';
                }
                if (RegExp(r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                        .hasMatch(value) ==
                    false) {
                  return 'Sila masukan format alamat email yang betul';
                }
                return null;
              },
            ),
          ),
          Container(
            alignment: Alignment.center,
            child: TextFormField(
              controller: _passwordController,
              decoration: InputDecoration(
                prefixIcon: Icon(Icons.vpn_key),
                suffixIcon: GestureDetector(
                    child:
                        Icon(_secure ? Icons.visibility : Icons.visibility_off),
                    onTap: _toggleSecure),
                labelText: 'Kata Laluan',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              obscureText: _secure,
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Sila masukan kata laluan';
                }
                if (value.length < 10) {
                  return 'Minimum 10 aksara';
                }
                if (value != _confirmPasswordController.text) {
                  return 'Kata laluan tidak sepadan';
                }
                return null;
              },
            ),
          ),
          Container(
            alignment: Alignment.center,
            child: TextFormField(
              controller: _confirmPasswordController,
              decoration: InputDecoration(
                prefixIcon: Icon(Icons.vpn_key),
                suffixIcon: GestureDetector(
                    child:
                        Icon(_secure ? Icons.visibility : Icons.visibility_off),
                    onTap: _toggleSecure),
                labelText: 'Sahkan Kata Laluan',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              obscureText: _secure,
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Sila masukan kata laluan';
                }
                if (value.length < 10) {
                  return 'Minimum 10 aksara';
                }
                if (value != _passwordController.text) {
                  return 'Kata laluan tidak sepadan';
                }
                return null;
              },
            ),
          ),
          Container(
              padding: EdgeInsets.fromLTRB(0.0, 10.0, 0.0, 0.0),
              alignment: Alignment.center,
              child: SizedBox(
                width: double.infinity, // match_parent
                child: RaisedButton(
                    color: Theme.of(context).primaryColor,
                    child: Text(
                      'Daftar Ahli',
                      style: TextStyle(color: Colors.white),
                    ),
                    onPressed: () async {
                      if (_formKey.currentState.validate()) {
                        await _submitForm();
                      }
                    }),
              ))
        ],
      ),
    );
  }

  @override
  void dispose() {
    _usernameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  Future _submitForm() async {
    FocusScope.of(context).requestFocus(new FocusNode());
    widget.loadingCb(true);
    final _jwttoken = await Api.getAuthToken();
    Map<String, dynamic> responseBody = {};
    try {
      final request = Api.postRegister();
      request.fields['username'] = _usernameController.text;
      request.fields['email'] = _emailController.text;
      request.fields['password'] = _passwordController.text;
      request.fields['confirm_password'] = _confirmPasswordController.text;
      request.headers['authorization'] = _jwttoken;
      final response = await request.send();
      Api.setAuthToken(response.headers['authorization']);
      responseBody = json.decode(await response.stream.bytesToString());
      widget.loadingCb(false);
      if (response.statusCode == 200) {
        if (responseBody['message'] != null) {
          Fluttertoast.showToast(
              msg: responseBody['message'], toastLength: Toast.LENGTH_LONG);
        }
        Navigator.of(context).pop();
      } else if (response.statusCode == 400) {
        if (responseBody['message'] != null) {
          Fluttertoast.showToast(
              msg: responseBody['message'], toastLength: Toast.LENGTH_LONG);
        }
        if (responseBody['errors'] != null) {
          if (responseBody['errors']['username'] != null) {
            Fluttertoast.showToast(
                msg: responseBody['errors']['username'],
                toastLength: Toast.LENGTH_SHORT);
          }
          if (responseBody['errors']['email'] != null) {
            Fluttertoast.showToast(
                msg: responseBody['errors']['email'],
                toastLength: Toast.LENGTH_SHORT);
          }
        }
      } else {
        Fluttertoast.showToast(
            msg: "Server Error", toastLength: Toast.LENGTH_LONG);
      }
    } on Exception {
      widget.loadingCb(false);
      Fluttertoast.showToast(
          msg: "Network Error", toastLength: Toast.LENGTH_LONG);
    }
  }

  void _toggleSecure() {
    setState(() {
      _secure = !_secure;
    });
  }
}
