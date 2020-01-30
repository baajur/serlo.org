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
  click,
  clickForNewPage,
  getByRole,
  getByLabelText,
  getByText,
  getAllByText,
  goto,
  getBySelector,
  login,
  logout,
  randomText,
  isVisible
} from '../_utils'

import { exampleApiParameters, pages, navigation, viewports } from '../_config'
import { forEach } from 'ramda'

describe('view exercises', () => {
  const data = {
    exercise: {
      id: '35573',
      content: 'Example test exercise',
      hintContent: 'Example hint',
      solutionContent: 'Example solution'
    },
    exerciseGroup: {
      id: '35580',
      content: 'Example group exercise'
    },
    groupedExercise: [
      {
        id: '35581',
        content: 'Subexercise 1',
        hintContent: 'Hint subexercise 1',
        solutionContent: 'Solution subexercise 1'
      },
      {
        id: '35584',
        content: 'Subexercise 2',
        hintContent: '',
        solutionContent: 'Solution subexercise 2'
      },
      {
        id: '35586',
        content: 'Subexercise 3',
        hintContent: '',
        solutionContent: 'Solution subexercise 3'
      }
    ]
  }
  beforeEach(async () => {
    await page.setViewport(viewports.desktop)
  })

  describe('view text exercise', () => {
    const path = '/' + data.exercise.id
    test('text exercise with hint and solution', async () => {
      const page = await goto(path)
      await expect(page).not.toMatchElement('h1')
      await expect(page).toMatchElement('*', { text: data.exercise.content })

      await getByText(page, 'Show hint').then(click)
      const hint = await getByText(page, data.exercise.hintContent)
      const hintVisible = await isVisible(hint)
      expect(hintVisible).toBe(true)

      await getByText(page, 'Show solution').then(click)
      const solution = await getByText(page, data.exercise.solutionContent)
      const solutionVisible = await isVisible(solution)
      expect(solutionVisible).toBe(true)
    })

    describe('text exercise has no heading on content-api requests', () => {
      test.each(exampleApiParameters)(
        'parameter %p is set',
        async contentApiParam => {
          const page = await goto(`${path}?${contentApiParam}`)
          await expect(page).not.toMatchElement('h1')
        }
      )
    })
  })

  describe('view text exercise group', () => {
    const path = '/' + data.exerciseGroup.id
    test('text exercise group with subexercises', async () => {
      const page = await goto(path)
      await expect(page).not.toMatchElement('h1')
      await expect(page).toMatchElement('*', {
        text: data.exerciseGroup.content
      })
      for (let i = 0; i < data.groupedExercise.length; i++) {
        await expect(page).toMatchElement('*', {
          text: data.groupedExercise[i].content
        })
      }

      await getByText(page, 'Show hint').then(click)
      const hint = await getByText(page, data.groupedExercise[0].hintContent)
      const hintVisible = await isVisible(hint)
      expect(hintVisible).toBe(true)

      const solutionHandles = await getAllByText(page, 'Show solution')
      for (const solutionHandle of solutionHandles) {
        await click(solutionHandle)
      }
      for (let i = 0; i < data.groupedExercise.length; i++) {
        const solution = await getByText(
          page,
          data.groupedExercise[i].solutionContent
        )
        const solutionVisible = await isVisible(solution)
        expect(solutionVisible).toBe(true)
      }
    })

    describe('text exercise group has no heading on content-api requests', () => {
      test.each(exampleApiParameters)(
        'parameter %p is set',
        async contentApiParam => {
          const page = await goto(`${path}?${contentApiParam}`)
          await expect(page).not.toMatchElement('h1')
        }
      )
    })
  })

  describe('view grouped text exercise', () => {
    const {
      id,
      content,
      hintContent,
      solutionContent
    } = data.groupedExercise[0]
    const path = '/' + id

    test('grouped exercise with hint and solution', async () => {
      const page = await goto(path)
      await expect(page).toMatchElement('*', { text: content })

      await getByText(page, 'Show hint').then(click)
      const hint = await getByText(page, hintContent)
      const hintVisible = await isVisible(hint)
      expect(hintVisible).toBe(true)

      await getByText(page, 'Show solution').then(click)
      const solution = await getByText(page, solutionContent)
      const solutionVisible = await isVisible(solution)
      expect(solutionVisible).toBe(true)
    })

    test('grouped exercise has page header with backlink', async () => {
      const page = await goto(path)
      await expect(page).toMatchElement('h1', { text: id })
      await expect(page).not.toMatchElement('*', {
        text: data.exerciseGroup.content
      })

      const exerciseGroup = await getBySelector(page, navigation.backLink).then(
        clickForNewPage
      )
      await expect(exerciseGroup).toMatchElement('*', {
        text: data.exerciseGroup.content
      })
    })

    describe('grouped exercise has no heading on content-api requests', () => {
      test.each(exampleApiParameters)(
        'parameter %p is set',
        async contentApiParam => {
          const page = await goto(`${path}?${contentApiParam}`)
          await expect(page).not.toMatchElement('h1', { text: id })
        }
      )
    })
  })
})

describe('create text-exercise', () => {
  afterEach(async () => {
    await logout()
  })

  test.each(['admin', 'english_langhelper'])('user is %p', async user => {
    const exercise = randomText('exercise content')
    const hint = randomText('hint')
    const solution = randomText('solution')

    await login(user)
    const topic = await goto(pages.e2eTopic.path)

    await getBySelector(topic, navigation.dropdownToggle).then(click)
    await page.waitForSelector('#subject-nav-wrapper .dropdown-menu')
    await getByText(topic, navigation.addContent).then(e => e.hover())
    const createPage = await getByText(topic, 'text-exercise').then(
      clickForNewPage
    )

    await getByRole(createPage, 'textbox').then(e => e.type(exercise))

    await getByText(createPage, 'Hinweis hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(hint))

    await getByText(createPage, 'Lösung hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(solution))

    await getBySelector(createPage, navigation.saveButton).then(click)
    await getByLabelText(createPage, 'Änderungen').then(e =>
      e.type(randomText())
    )
    await createPage.$$('input[type=checkbox]').then(c => c[0].click())
    await createPage.$$('input[type=checkbox]').then(c => c[3].click())

    const success = await getByText(createPage, 'Speichern', {
      selector: 'button'
    }).then(clickForNewPage)

    expect(success).toMatchElement('p', {
      text: 'Your revision has been saved and is available'
    })

    await expect(success).toHaveTitle('Math text-exercise')

    await expect(success).toMatchElement('*', { text: exercise })
    await expect(success).toMatchElement('*', { text: hint })
    await expect(success).toMatchElement('*', { text: solution })
  })
})

describe('create grouped text-exercise', () => {
  afterEach(async () => {
    await logout()
  })
  test.each(['admin', 'english_langhelper'])('user is %p', async user => {
    const exercise = randomText('exercise content')
    const subexercise1 = randomText('subexercise1')
    const subexercise2 = randomText('subexercise2')
    const hint1 = randomText('hint1')
    const solution1 = randomText('solution1')

    await login(user)
    const topic = await goto(pages.e2eTopic.path)

    await getBySelector(topic, navigation.dropdownToggle).then(click)
    await page.waitForSelector('#subject-nav-wrapper .dropdown-menu')
    await getByText(topic, navigation.addContent).then(e => e.hover())
    const createPage = await getByText(topic, 'text-exercise-group').then(
      clickForNewPage
    )

    await getByRole(createPage, 'textbox').then(e => e.type(exercise))

    await getByText(createPage, 'Teilaufgabe hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(subexercise1))

    await getByText(createPage, 'Hinweis hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(hint1))

    await getByText(createPage, 'Lösung hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(solution1))

    await getByText(createPage, 'Teilaufgabe hinzufügen').then(click)
    await page.waitFor(100)
    await getByRole(createPage, 'textbox').then(t => t.type(subexercise2))

    await getBySelector(createPage, navigation.saveButton).then(click)
    await getByLabelText(createPage, 'Änderungen').then(e =>
      e.type(randomText())
    )
    await createPage.$$('input[type=checkbox]').then(c => c[0].click())
    await createPage.$$('input[type=checkbox]').then(c => c[3].click())

    const success = await getByText(createPage, 'Speichern', {
      selector: 'button'
    }).then(clickForNewPage)

    expect(success).toMatchElement('p', {
      text: 'Your revision has been saved and is available'
    })
    const result = await getBySelector(success, navigation.backLink).then(
      clickForNewPage
    )
    await expect(result).toHaveTitle('Math text-exercise-group')

    await expect(result).toMatchElement('*', { text: exercise })
    await expect(result).toMatchElement('*', { text: subexercise1 })
    await expect(result).toMatchElement('*', { text: subexercise2 })
    await expect(result).toMatchElement('*', { text: hint1 })
    await expect(result).toMatchElement('*', { text: solution1 })
  })
})
