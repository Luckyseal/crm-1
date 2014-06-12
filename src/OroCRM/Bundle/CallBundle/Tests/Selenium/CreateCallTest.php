<?php

namespace OroCRM\Bundle\CallBundle\Tests\Selenium;

use Oro\Bundle\TestFrameworkBundle\Test\Selenium2TestCase;

class CreateCallTest extends Selenium2TestCase
{
    /**
     * Test new Call creation functionality
     * @return string
     */
    public function testCreateCall()
    {
        $callSubject = 'Call_'.mt_rand();
        $phoneNumber = mt_rand(100, 999).'-'.mt_rand(100, 999).'-'.mt_rand(1000, 9999);

        $login = $this->login();
        $login->openCalls('OroCRM\Bundle\CallBundle')
            ->add()
            ->assertTitle('Log Call - Calls - Activities')
            ->setCallSubject($callSubject)
            ->setPhoneNumber($phoneNumber)
            ->save()
            ->assertMessage('Call logged successfully')
            ->assertTitle('Calls - Activities')
            ->close();

        return $callSubject;
    }

    /**
     * Test update existing Call
     * @depends testCreateCall
     * @param $callSubject
     * @return string
     */
    public function testUpdateCall($callSubject)
    {
        $newCallSubject = 'Update_' . $callSubject;

        $login = $this->login();
        $login->openCalls('OroCRM\Bundle\CallBundle')
            ->filterBy('Subject', $callSubject)
            ->open(array($callSubject))
            ->assertTitle($callSubject . ' - Edit - Calls - Activities')
            ->setCallSubject($newCallSubject)
            ->save()
            ->assertMessage('Call logged successfully')
            ->assertTitle('Calls - Activities')
            ->close();

        return $newCallSubject;
    }

    /**
     * Test delete existing Call
     * @depends testUpdateCall
     * @param $newCallSubject
     */
    public function testDeleteCall($newCallSubject)
    {
        $login = $this->login();
        $login->openCalls('OroCRM\Bundle\CallBundle')
            ->filterBy('Subject', $newCallSubject)
            ->deleteEntity(array($newCallSubject))
            ->assertMessage('Item deleted');

        $login->openCalls('OroCRM\Bundle\CallBundle')
            ->filterBy('Subject', $newCallSubject)
            ->assertNoDataMessage('No calls was found to match your search. Try modifying your search criteria...');
    }
}