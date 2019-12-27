import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart' show CupertinoPageRoute;
import 'package:flutterci/navigation/fragments.dart';
import 'package:flutterci/navigation/screens.dart';
import 'package:flutterci/config.dart';
import 'package:flutter/foundation.dart';
import 'package:provider/provider.dart';
import 'package:flutterci/state/provider_state.dart';

void main() => runApp(MyApp());

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(builder: (_) => Auth()),
      ],
      child: MaterialApp(
        title: Config.APP_NAME,
        theme: ThemeData(
          primarySwatch: Config.THEME_COLOR,
        ),
        home: MyHomePage(),
      ),
    );
  }
}

class DrawerItem {
  String title;
  IconData icon;
  Function body;
  bool requireLoggedIn;
  DrawerItem(this.title, this.icon, this.body, this.requireLoggedIn);
}

class MyHomePage extends StatefulWidget {
  final drawerFragments = [
    new DrawerItem(
        "Home",
        Icons.home,
        (Function loadingCb) => new Home(title: 'Register', loadingCb: loadingCb),
        null
    ),
  ];

  final drawerScreens = [
    new DrawerItem(
        "Register",
        Icons.person_add,
        (Function loadingCb) =>
            new RegisterPage(title: 'Register', loadingCb: loadingCb),
        false),
    new DrawerItem(
        "Login",
        Icons.exit_to_app,
        (Function loadingCb) =>
            new LoginPage(title: 'Login', loadingCb: loadingCb),
        false),
    new DrawerItem(
        "Forgot Password",
        Icons.lock_open,
        (Function loadingCb) =>
            new ForgotPassword(title: 'Lupa Kata Laluan', loadingCb: loadingCb),
        false),
  ];

  MyHomePage({Key key}) : super(key: key);

  @override
  _MyHomePageState createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {

  int _selectedDrawerFragmentIndex = 0;

  void _loadingDialog(bool show) {
    if (show == true) {
      showDialog(
        barrierDismissible: false,
        context: context,
        builder: (BuildContext _) {
          return AlertDialog(
            content: Container(child: new LinearProgressIndicator()),
          );
        },
      );
    } else {
      Navigator.of(context).pop();
    }
  }

  void _logOut() {
    Navigator.of(context).pop();
    showDialog(
      barrierDismissible: true,
      context: context,
      builder: (BuildContext _) {
        return AlertDialog(
          title: Text("Are you sure to logout ?",
              style: TextStyle(fontSize: 16.0)),
          actions: <Widget>[
            new FlatButton(
              child: new Text("Cancel"),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            new FlatButton(
              child: new Text("Yes"),
              onPressed: () {
                Navigator.of(context).pop();
                Provider.of<Auth>(context).signOut(_loadingDialog);
              },
            )
          ],
        );
      },
    );
  }

  _getDrawerFragmentWidgetIndex(int pos) {
    if (widget.drawerFragments[pos] != null) {
      return widget.drawerFragments[pos].body(_loadingDialog);
    } else {
      return new Text("Error");
    }
  }

  _onSelectFragment(int index) {
    setState(() => _selectedDrawerFragmentIndex = index);
    Navigator.of(context).pop();
  }

  _onSelectScreen(int index) {
    if (widget.drawerScreens[index] != null) {
      Navigator.of(context).pop(); // close drawer
      Navigator.push(
          context,
          CupertinoPageRoute(
              builder: (BuildContext context) =>
                  widget.drawerScreens[index].body(_loadingDialog)));
    }
  }

  @override
  Widget build(BuildContext context) {
    final _auth = Provider.of<Auth>(context);

    List<Widget> drawerOptions = [];

    for (var i = 0; i < widget.drawerFragments.length; i++) {
      var d = widget.drawerFragments[i];
      if (widget.drawerFragments[i].requireLoggedIn != null &&
          widget.drawerFragments[i].requireLoggedIn != _auth.loggedIn) {
        continue;
      }
      drawerOptions.add(new ListTile(
        leading: new Icon(d.icon),
        title: new Text(d.title),
        selected: i == _selectedDrawerFragmentIndex,
        onTap: () => _onSelectFragment(i),
      ));
    }

    for (var i = 0; i < widget.drawerScreens.length; i++) {
      var d = widget.drawerScreens[i];
      if (widget.drawerScreens[i].requireLoggedIn != null &&
          widget.drawerScreens[i].requireLoggedIn != _auth.loggedIn) {
        continue;
      }
      drawerOptions.add(new ListTile(
        leading: new Icon(d.icon),
        title: new Text(d.title),
        onTap: () => _onSelectScreen(i),
      ));
    }

    if (_auth.loggedIn == true) {
      drawerOptions.add(new ListTile(
        leading: new Icon(Icons.exit_to_app),
        title: new Text("Logout"),
        onTap: () {
          _logOut();
        },
      ));
    }

    return new Scaffold(
      //appBar: new AppBar(
      //  title: new Text(widget.drawerFragments[_selectedDrawerFragmentIndex].title),
      //),
      drawer: new SizedBox(
        width: MediaQuery.of(context).size.width * 0.80,
        child: new Drawer(
          child: new Column(
            children: <Widget>[
              new UserAccountsDrawerHeader(
                accountName: new Text('Hi, ' + _auth.username,
                    style: TextStyle(fontSize: 16)),
                accountEmail: new Text(_auth.email,
                    style:
                        TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                currentAccountPicture: CircleAvatar(
                  backgroundColor: Colors.white,
                  backgroundImage: _auth.avatar,
                ),
              ),
              new Column(children: drawerOptions)
            ],
          ),
        ),
      ),
      body: _getDrawerFragmentWidgetIndex(_selectedDrawerFragmentIndex),
    );
  }
}
