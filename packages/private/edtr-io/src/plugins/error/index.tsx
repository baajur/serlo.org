/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2019 Serlo Education e.V.
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
 * @copyright Copyright (c) 2013-2019 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
import {
  StatefulPlugin,
  StatefulPluginEditorProps,
  StateType
} from '@edtr-io/core'
import * as React from 'react'
import { styled } from '@edtr-io/renderer-ui'

export const errorState = StateType.object({
  plugin: StateType.string(),
  state: StateType.scalar<unknown>({})
})

const Error = styled.div({
  backgroundColor: 'rgb(204,0,0)',
  color: '#fff'
})
export const ErrorRenderer: React.FunctionComponent<
  StatefulPluginEditorProps<typeof errorState>
> = props => {
  return (
    <Error>
      <p>
        <strong>Beim Konvertieren ist ein Fehler aufgetreten!</strong>
      </p>
      <p>
        Das Plugin {props.state.plugin()} konnte nicht konvertiert werden. Es
        enthielt folgende Daten:
      </p>
      <code>{JSON.stringify(props.state.state())}</code>
      <p>Bitte wende dich an einen Entwickler.</p>
    </Error>
  )
}

export const errorPlugin: StatefulPlugin<typeof errorState> = {
  Component: ErrorRenderer,
  state: errorState
}
