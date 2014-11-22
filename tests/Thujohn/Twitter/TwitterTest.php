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

    public function paramTest($endpoint, $testedMethod, $params)
    {
        $twitter = $this->getTwitterExpecting($endpoint, $params);

        $twitter->$testedMethod($params);
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

    /**
     * @expectedException Exception
     */
    public function testGetUsersLookupInvalid()
    {
        // Requires either a list id and user_id
        $twitter = $this->getTwitter();

        $twitter->getUsersLookup(array(
            'include_entities' => true
        ));
    }

    public function testGetListWithId()
    {
        $this->paramTest('lists/show', 'getList', array(
            'list_id'=>1
        ));
    }

    public function testGetListWithSlugAndName()
    {
        $this->paramTest('lists/show', 'getList', array(
            'slug' => 'sugar_to_kiss',
            'owner_screen_name' => 'elwood'
        ));
    }

    public function testGetListWithSlugAndUserId()
    {
        $this->paramTest('lists/show', 'getList', array(
            'slug' => 'sugar_to_kiss',
            'owner_id' => 1
        ));
    }

    /**
     * @expectedException Exception
     */
    public function testGetListInvalid()
    {
        $this->paramTest('lists/show', 'getList', array(
            'slug' => 'sweetheart_to_miss'
        ));
    }

    /*
    * getListMembers can accept list_id, or slug and user name, or slug and user id
    */
    public function testGetListMembersWithId()
    {
        $this->paramTest('lists/members', 'getListMembers', array(
            'list_id' => 1
        ));
    }

    public function testGetListMembersWithSlugAndName()
    {
        $this->paramTest('lists/members', 'getListMembers', array(
            'slug' => 'sugar_to_kiss',
            'owner_screen_name' => 'elwood'
        ));
    }

    public function testGetListMembersWithSlugAndUserId()
    {
        $this->paramTest('lists/members', 'getListMembers', array(
            'slug' => 'sugar_to_kiss',
            'owner_id' => 1
        ));
    }

    /**
     * @expectedException Exception
     */
    public function testGetListMembersInvalid()
    {
        $this->paramTest('lists/members', 'getListMembers', array(
            'slug' => 'sweetheart_to_miss'
        ));
    }

    public function testGetListMember()
    {
        // require

        // $twitter = $this->getTwitterExpecting('lists/members/show', array(
        //     ''
        // ));

        // $twitter->getUsersLookup(array(
        //     'screen_name' => implode(',', array('me', 'you', 'everybody'))
        // ));

    }
}
