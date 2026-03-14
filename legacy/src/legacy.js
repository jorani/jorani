window.Cookies = require('js-cookie')
window.ClipboardJS = require('clipboard')
window.Hammer = require('hammerjs')
window.moment = require('moment')
window.$ = window.jQuery = require('jquery-legacy')

//Load JQuery plugins
require('imports-loader?imports=default|jQuery|$!select2')
require('imports-loader?imports=default|jQuery|$!jstree')

import css from '../assets/css/legacy.scss'
