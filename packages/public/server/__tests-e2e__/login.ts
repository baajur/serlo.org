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
import {
  pages,
  viewports,
  users,
  elements,
  alertBoxes,
  wrongUsers,
  wrongPasswords,
  testingServerUrl,
} from './_config'
import {
  clickForNewPage,
  getByPlaceholderText,
  getBySelector,
  getByText,
  getByRole,
  goto,
} from './_utils'

const examplePages = ['/', '/math', '/math/geometry/triangles']

describe('login process', () => {
  describe.each(users)('user is %p', (user) => {
    test.each(examplePages)('start page is %p', async (startPath) => {
      await page.setViewport(viewports.desktop)
      const firstPage = await goto(startPath)

      expect(firstPage).toHaveUrlPath(startPath)

      const loginPage = await elements
        .getLoginButton(firstPage)
        .then(clickForNewPage)

      expect(loginPage).toHaveUrlPath(pages.login.path)

      const { buttonLogin, inputUser, inputPassword } = pages.login.identifier

      await getByPlaceholderText(loginPage, inputUser).then((e) => e.type(user))
      await getByPlaceholderText(loginPage, inputPassword).then((e) =>
        e.type(pages.login.defaultPassword)
      )
      const afterLoginPage = await getByText(loginPage, buttonLogin).then(
        clickForNewPage
      )
      await expect(await elements.getLogoutButton(afterLoginPage)).toBeDefined()

      const userPage = await elements
        .getProfileButton(afterLoginPage)
        .then(clickForNewPage)

      expect(userPage).toHaveUrlPath('/user/me')

      await expect(userPage).toMatchElement('h1', { text: user })
    })
  })

  describe('missing and wrong Data', () => {
    const { buttonLogin, inputUser, inputPassword } = pages.login.identifier

    describe.each(wrongUsers)('user is %p', (user) => {
      test.each(wrongPasswords)('password is %p', async (password) => {
        await page.setViewport(viewports.desktop)
        const loginPage = await goto('/auth/login')

        await getByPlaceholderText(loginPage, inputUser).then((e) =>
          e.type(user)
        )
        await getByPlaceholderText(loginPage, inputPassword).then((e) =>
          e.type(password)
        )

        if (user.length == 0 || password.length == 0) {
          //Check if stayed on the page
          expect(page.url().localeCompare(testingServerUrl + pages.login.path))
        } else {
          const afterLoginPage = await getByText(loginPage, buttonLogin).then(
            clickForNewPage
          )
          //Check if stayed on the page
          expect(afterLoginPage).toHaveUrlPath(pages.login.path)
          //Check alertbox
          expect(await getByText(afterLoginPage, alertBoxes.wrongCombination))
            .toBeDefined
        }
      })
    })
  })
})
