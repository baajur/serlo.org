/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2020 Serlo Education e.V.
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
 * @copyright Copyright (c) 2013-2020 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
import * as Sentry from '@sentry/browser'

const { version } = require('../../../package.json')

Sentry.init({
  dsn:
    process.env.NODE_ENV === 'production'
      ? 'https://019a6c4e5ac24e26a6b2391398c445bd@sentry.io/1518830'
      : undefined,
  release: `serlo-org-client@${version}`,
  whitelistUrls: ['serlo.org', 'serlo-development.dev', 'serlo-staging.dev'],
  beforeSend(event, hint) {
    const error = hint?.originalException
    if (
      typeof error === 'object' &&
      error?.message.match(/Non-Error promise rejection/)
    ) {
      return null
    }
    return event
  },
})

export { Sentry }
