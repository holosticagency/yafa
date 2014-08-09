# YAPI
_Yafa API_

## About
yafa api or yapi is a set of 'mini applications' that are used by yafa core and yafa applications for tasks like caching, user authentication and authorization, string translation, layout rendering, config etc.

core and apps communicate with each yapi using a standard set of methods and each yapi must respond in the same way (see yapi/README) each yapi can have multiple versions and config will determine which will be used.

## Usage
for yapi caled test create `test` folder under `yapi`. this folder should contain factory class called `TestFactory` in file called `TestFactory.php` (see example) that will create instance for test yapi and files called `yapi_test_XXX.php` where XXX is version number in format 1.2.3 (see [semver.org][3]) that will contain actual classes for this yapi. class should be in namespace `yapi\test` should be called `YapiTest` (no mater what version) and should extends `\YapiNull` class

communication between app and api is done with the usage of `set_request()` & `get_responde()`



- - -
_thanks for flying yafa airways ;)_
- - -

## License
see [MIT License][4] or LICENSE file

## TODO
- finish this README

## Note
- use [hashify.me][2] to write README
- follow the rules of Semantic Versioning at [semver.org][3]

[0]:http://gy.rs
[0.5]:https://bitbucket.org/plz
[0.7]:http://mercurial.selenic.com
[1]:http://holisticagency.com/yafa/wiki
[1.5]:http://i.imgur.com/oTf59g6.jpg?1
[2]:http://hashify.me
[3]:http://semver.org
[4]:http://opensource.org/licenses/MIT