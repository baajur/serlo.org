/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2021 Serlo Education e.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Copyright (c) 2013-2021 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
import $ from 'jquery'
import _ from 'underscore'

import pluginHtmlTemplate from '../../templates/plugins/injection/injection_plugin_default.html'
import EditorPlugin from '../serlo_texteditor_plugin'

var DefaultInjectionPlugin, titleRegexp, hrefRegexp

titleRegexp = new RegExp(/\[[^\]]*\]\(/)
hrefRegexp = new RegExp(/\([^)]*\)/)

DefaultInjectionPlugin = function (data) {
  this.state = 'default-injection'
  this.info = data || {}
  this.init()
}

DefaultInjectionPlugin.prototype = new EditorPlugin()
DefaultInjectionPlugin.prototype.constructor = DefaultInjectionPlugin

DefaultInjectionPlugin.prototype.init = function () {
  var that = this

  that.template = _.template(pluginHtmlTemplate)

  that.data.name = 'Injection'
}

DefaultInjectionPlugin.prototype.activate = function (token) {
  var that = this
  var title
  var href

  that.data.content = token.string
  title = _.first(that.data.content.match(titleRegexp))
  that.data.title = title.substr(1, title.length - 3)

  href = _.first(that.data.content.match(hrefRegexp))
  that.data.href = href.substr(1, href.length - 2)

  that.$el = $(that.template(that.data))

  that.$el.on('click', '.btn-save', function () {
    that.save()
  })

  that.$el.on('click', '.btn-cancel', function (e) {
    e.preventDefault()
    that.trigger('close')
  })
}

DefaultInjectionPlugin.prototype.save = function () {
  this.setAndValidateContent()
  this.trigger('save', this)
}

DefaultInjectionPlugin.prototype.setAndValidateContent = function () {
  var href = $('.href', this.$el).val()

  if (href.substr(0, 1) !== '/') {
    href = '/' + href
  }

  this.data.content = '>[' + $('.title', this.$el).val() + '](' + href + ')'
}

EditorPlugin.DefaultInjection = DefaultInjectionPlugin
