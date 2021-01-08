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
import { goto, getByItemType } from '../_utils'

const eventUrl = 'http://de.serlo.localhost:4567/35554'
const eventItemType = 'http://schema.org/Event'

test('view page of events', async () => {
  const eventPage = await goto(eventUrl)
  const event = await getByItemType(eventPage, eventItemType)
  await expect(event).toMatchElement('h1', { text: 'Beispielveranstaltung' })
  await expect(event).toMatchElement('*', {
    text: 'Redaktionssitzung in Münster',
  })
})
