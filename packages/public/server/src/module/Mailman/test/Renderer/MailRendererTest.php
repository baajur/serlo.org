<?php
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
namespace module\Mailman\test\MailmanTest\Renderer;

use Doctrine\Common\Collections\ArrayCollection;
use Mailman\Renderer\MailRenderer;
use Notification\Entity\Notification;
use PHPUnit\Framework\TestCase;
use User\Entity\User;
use Zend\View\Renderer\RendererInterface;

class MailRendererTest extends TestCase
{
    /**
     * @var MailRenderer
     */
    private $mailRenderer;

    protected function setUp()
    {
        parent::setUp();

        $this->renderer = $this->createMock(RendererInterface::class);

        $this->mailRenderer = new MailRenderer($this->renderer);
    }

    /**
     * @param string $folder
     * @param array $data
     * @dataProvider providerAllData
     */
    public function testRenderForwarding($folder, $data)
    {
        $this->mailRenderer->setTemplateFolder($folder);

        $this->renderer
            ->expects($this->exactly(3))
            ->method('render')
            ->with($this->isInstanceOf('\Zend\View\Model\ViewModel'))
            ->will($this->returnValue('subject'));

        $this->mailRenderer->renderMail($data);
    }

    /**
     * @param string $folder
     * @dataProvider providerAllData
     */
    public function testTemplatesExist($folder)
    {
        $base = 'module/Ui/templates/';
        $this->assertFileExists($base . $folder . '/subject.twig');
        $this->assertFileExists($base . $folder . '/body.twig');
        $this->assertFileExists($base . $folder . '/plain.twig');
    }

    public function providerUserMailData()
    {
        $userDummy = new User();
        $userDummy->setUsername('UserDummy');

        $data = [
            'body' => [
                'user' => $userDummy,
            ],
        ];

        return [
            ['mailman/messages/welcome', $data],
            ['mailman/messages/register', $data],
            ['mailman/messages/restore-password', $data],
        ];
    }

    public function providerNotificationMailData()
    {
        $userDummy = new User();
        $userDummy->setUsername('UserDummy');
        $contentNotificationDummy = new Notification();
        $discussionNotificationDummy = new Notification();
        $data = [
            'body' => [
                'user' => $userDummy,
                'contentNotifications' => new ArrayCollection([
                    $contentNotificationDummy,
                ]),
                'discussionNotifications' => new ArrayCollection([
                    $discussionNotificationDummy,
                ]),
            ],
        ];
        return [['mailman/messages/notification', $data]];
    }

    public function providerAllData()
    {
        return array_merge(
            $this->providerUserMailData(),
            $this->providerNotificationMailData()
        );
    }
}
