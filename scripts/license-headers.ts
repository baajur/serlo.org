import { updateCopyrightHeader } from '@inyono/copyright-headers'
import glob from 'glob'
import * as path from 'path'
import * as util from 'util'

const g = util.promisify(glob)

const root = path.join(__dirname, '..')

const year = new Date().getFullYear()
const lines = [
  'This file is part of Serlo.org.',
  '',
  `Copyright (c) 2013-${year} Serlo Education e.V.`,
  '',
  'Licensed under the Apache License, Version 2.0 (the "License")',
  'you may not use this file except in compliance with the License',
  'You may obtain a copy of the License at',
  '',
  '    http://www.apache.org/licenses/LICENSE-2.0',
  '',
  'Unless required by applicable law or agreed to in writing, software',
  'distributed under the License is distributed on an "AS IS" BASIS',
  'WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.',
  'See the License for the specific language governing permissions and',
  'limitations under the License.',
  '',
  `@copyright Copyright (c) 2013-${year} Serlo Education e.V.`,
  '@license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0',
  '@link      https://github.com/serlo-org/serlo.org for the canonical source repository',
]

g('**/*@(.js|.ts|.tsx|.php|.twig|.phtml)', {
  cwd: root,
  ignore: [
    '**/*.config.js',
    '**/dist*/**',
    '**/node_modules/**',
    'packages/public/client/src/thirdparty/**',
    'packages/public/server/dev-tools/vendor/**',
    'packages/public/server/src/vendor/**',
    'scripts/**',
  ],
}).then((files) => {
  return files.map((file) => {
    const filePath = path.join(root, file)

    return updateCopyrightHeader(filePath, {
      lines,
      shouldUpdate: (content) => {
        return (
          content.includes('Athene2') ||
          content.includes('Serlo') ||
          content.includes('serlo')
        )
      },
    })
  })
})
