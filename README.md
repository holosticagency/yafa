# YAFA
_Yet Another Framework Acronym_

## About
yafa suppose to be small, readable, fast and modular PHP framework. It is developed for the [gy.rs][0] project.

yafa consist of 4 parts:

1. yafa core (requester, responder, router, loader...)
2. yafa api [yapi] (internal api used by core and applications)
3. vendor (libs used from core and yapi, it's not really part of yafa but yafa depend on it)
4. apps (create one of your own or use/fork some from [bitbucket.org/plz][0.5])

yafa core and yafa api are in one repository while vendor and app have there own.

###`yafa core`
yafa core is hart of any yafa project and is relatively small, it has only couple of php files with sum of LOC less then 2k. yafa core should start application, handle request and respond and delegate all other stuff to app.

###`yapi`
yafa api or yapi is a set of 'mini applications' that are used by yafa core and yafa applications for tasks like caching, user authentication and authorization, string translation, layout rendering, config etc.

core and apps communicate with each yapi using a standard set of methods and each yapi must respond in the same way (see yapi/README) each yapi can have multiple versions and config will determine which will be used.

###`vendor`
vendor contain 3th party libs that are used from core and yapi (orm, profiling etc). app vendor libs should go to vendor dirs inside `app/`.

###`app`
in app part are applications that work on top of yafa. there can be more then one application and which one will be used is determine by defining `YAFA_APP_DIR` in init (yafa core).

## Requirements
1. PHP 5.3+ (for yafa core)
2. see `yapis/README` for yafa api requirements

## Install
yafa is hosted on [https://github.com/holosticagency/yafa][0.5] and is [git][0.7] repository.

1. get yafa:
    - composer: add "holisticagency/yafa": "dev-master" to require
    - git: `git clone git@github.com:holosticagency/yafa.git`

## Usage
short usage info, for more see wiki pages at [HolisticAgency.com/yafa/wiki][1]

yafa shema ([http://i.imgur.com/oTf59g6.jpg?1][1.5]):
![http://i.imgur.com/oTf59g6.jpg?1][1.5]



- - -
_thanks for flying yafa airways ;)_
- - -

## License
see [MIT License][4] or LICENSE file

## TODO
- create one-file-install
- refactor urls in this readme

## Note
- use [hashify.me][2] to write README
- follow the rules of Semantic Versioning at [semver.org][3]

[0]:http://gy.rs
[0.5]:https://github.com/holosticagency/yafa
[0.7]:http://git-scm.com/
[1]:http://holisticagency.com/yafa/wiki
[1.5]:http://i.imgur.com/oTf59g6.jpg?1
[2]:http://hashify.me
[3]:http://semver.org
[4]:http://opensource.org/licenses/MIT