<?php


class TwitterTest extends \PHPUnit_Framework_TestCase
{
    protected function getTwitter()
    {
        return $this->getMockBuilder('Thujohn\Twitter\Twitter')
                    ->setMethods(array('query'))
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    protected function getTwitterExpecting($endpoint, array $queryParams)
    {
        $twitter = $this->getTwitter();
        $twitter->expects($this->once())
                ->method('query')
                ->with(
                    $endpoint,
                    $this->anything(),
                    $queryParams
                );
        return $twitter;
    }

    public function testGetUsersWithScreenName()
    {
        $twitter = $this->getTwitterExpecting('users/show', array(
            'screen_name' => 'my_screen_name'
        ));

        $twitter->getUsers(array(
            'screen_name' => 'my_screen_name'
        ));
    }

    public function testGetUsersWithId()
    {
        $twitter = $this->getTwitterExpecting('users/show', array(
            'user_id' => 1234567890
        ));

        $twitter->getUsers(array(
            'user_id' => 1234567890
        ));
    }

    /**
     * @expectedException Exception
     */
    public function testGetUsersInvalid()
    {
        $twitter = $this->getTwitter();

        $twitter->getUsers(array(
            'include_entities' => true
        ));
    }

    public function testGetUsersLookupWithIds()
    {
        $twitter = $this->getTwitterExpecting('users/lookup', array(
            'user_id' => '1,2,3,4'
        ));

        $twitter->getUsersLookup(array(
            'user_id' => implode(',', array(1, 2, 3, 4))
        ));
    }

    public function testGetUsersLookupWithScreenNames()
    {
        $twitter = $this->getTwitterExpecting('users/lookup', array(
            'screen_name' => 'me,you,everybody'
        ));

        $twitter->getUsersLookup(array(
            'screen_name' => implode(',', array('me', 'you', 'everybody'))
        ));
    }

    public function testGetList()
    {
        // lists/show can accept either a list id...
        $twitter - $this->getTwitterExpecting('lists/show', array(
            'list_id' => 1
        ));

        // Or a list_slug and owner_screen_name...
        $twitter - $this->getTwitterExpecting('lists/show', array(
            'list_slug' => 'loves_somebody',
            'owner_screen_name' => 'elwood'
        ));

        // Or a list_slug and owner_id
        $twitter - $this->getTwitterExpecting('lists/show', array(
            'list_slug' => 'loves_somebody',
            'owner_id' => 1
        ));

    }

    /**
     * @expectedException Exception
     */
    public function testGetUsersLookupInvalid()
    {
        $twitter = $this->getTwitter();

        $twitter->getUsersLookup(array(
            'include_entities' => true
        ));
    }
}
