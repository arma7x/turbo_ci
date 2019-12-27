import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:flutterci/api.dart';
import 'package:flutterci/state/provider_state.dart';

class LoginPage extends StatefulWidget {
  LoginPage({Key key, this.title, this.loadingCb}) : super(key: key);

  final String title;
  final Function loadingCb;

  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
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
                  children: <Widget>[_LoginForm(loadingCb: widget.loadingCb)],
                )))));
  }
}

class _LoginForm extends StatefulWidget {
  final Function loadingCb;

  _LoginForm({Key key, this.loadingCb}) : super(key: key);

  @override
  State<StatefulWidget> createState() => _LoginFormState();
}

class _LoginFormState extends State<_LoginForm> {

  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

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
              controller: _emailController,
              decoration: InputDecoration(
                prefixIcon: Icon(Icons.email),
                labelText: 'Email Address',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Please enter email adrress';
                }
                if (RegExp(r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                        .hasMatch(value) ==
                    false) {
                  return 'Email address is invalid';
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
                labelText: 'Password',
                contentPadding: EdgeInsets.fromLTRB(0.0, 5.0, 0.0, 5.0),
              ),
              obscureText: _secure,
              validator: (String value) {
                if (value.isEmpty) {
                  return 'Please enter your password';
                }
                if (value.length < 10) {
                  return 'Minimum 10 character';
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
                      'Login',
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
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future _submitForm() async {
    FocusScope.of(context).requestFocus(new FocusNode());
    widget.loadingCb(true);
    final _jwttoken = await Api.getAuthToken();
    Map<String, dynamic> responseBody = {};
    try {
      final request = Api.postLogin();
      request.fields['email'] = _emailController.text;
      request.fields['password'] = _passwordController.text;
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
        Provider.of<Auth>(context, listen: false).whoAmI();
        Navigator.of(context).pop();
      } else if (response.statusCode == 400) {
        if (responseBody['message'] != null) {
          Fluttertoast.showToast(
              msg: responseBody['message'], toastLength: Toast.LENGTH_LONG);
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
