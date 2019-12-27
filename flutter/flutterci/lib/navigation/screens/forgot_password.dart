import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:flutterci/api.dart';

class ForgotPassword extends StatefulWidget {
  ForgotPassword({Key key, this.title, this.loadingCb}) : super(key: key);

  final String title;
  final Function loadingCb;

  @override
  _ForgotPasswordState createState() => _ForgotPasswordState();
}

class _ForgotPasswordState extends State<ForgotPassword> {
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
  final TextEditingController _emailController = TextEditingController();

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
              padding: EdgeInsets.fromLTRB(0.0, 10.0, 0.0, 0.0),
              alignment: Alignment.center,
              child: SizedBox(
                width: double.infinity, // match_parent
                child: RaisedButton(
                    color: Theme.of(context).primaryColor,
                    child: Text(
                      'Request Recovery Link',
                      style: TextStyle(color: Colors.white),
                    ),
                    onPressed: () async {
                      if (_formKey.currentState.validate()) {
                        await _submitForm();
                        //Toast.show(status, context, duration: 5);
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
    super.dispose();
  }

  Future _submitForm() async {
    FocusScope.of(context).requestFocus(new FocusNode());
    widget.loadingCb(true);
    final _jwttoken = await Api.getAuthToken();
    Map<String, dynamic> responseBody = {};
    try {
      final request = Api.postForgotPassword();
      request.fields['email'] = _emailController.text;
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
}
