<?php
/**
 */
class PluginIntroFriendTable extends Doctrine_Table
{
  /**
   * not friend delete essay
   * @param int $id check member id
   */
  public function deleteCheck($memberIdTo)
  {
    $introFriends = Doctrine::getTable('MemberRelationship')->createQuery()
      ->where('member_id_to = ?', $memberIdTo)
      ->leftJoin('MemberRelationship')
      ->addWhere('is_friend = ?', false)
      ->execute();

    foreach ($introFriends as $introFriend) {
      $introFriend->delete();
    }
  }

  /**
   * Get introFriend from member_id_to
   * @param int $memberIdTo member id
   * @return array array
   */
  public function getByTo($memberIdTo)
  {
    $this->deleteCheck($memberIdTo);
    $introFriends = $this->createQuery()
      ->where('member_id_to = ?', $memberIdTo)
      ->orderBy('id')
      ->execute();

    $list = array();
    foreach ($introFriends as $i => $introFriend) {
      $list[$i] = array();
      $list[$i]['essay'] = $introFriend->getContent();
      $list[$i]['member'] = $introFriend->getMember_2();
    }

    return $list;
  }

  /**
   * Get ByTo from Component
   * @param int $memberIdTo member id
   * @return array object array
   */
  public function getComponentByTo($memberIdTo)
  {
    $this->deleteCheck($memberIdTo);
    $introFriends = $this->createQuery()
      ->where('member_id_to = ?', $memberIdTo)
      ->orderBy('id')
      ->limit(sfConfig::get('app_max_introfriend'))
      ->execute();

    $list = array();
    foreach ($introFriends as $i => $introFriend)
    {
      $list[$i] = array();
      $string = $introFriend->getContent();
      $list[$i]['member'] = $introFriend->getMember_2();
      for ($j = 0, $pos = 0; $j < sfconfig::get('app_line_introfriend'); $j++)
      {
        $pos = mb_strpos($string, "\n", $pos + 1);
        if (!$pos) { break; }
      }
      if ($pos) { $string = mb_substr($string, 0, $pos - 1); }
      $list[$i]['essay'] = $string;
    }

    return $list;
  }

  /**
   * Get introFriend from member_id_from and member_id_to
   * @param int $memberIdFrom member id
   * @param int $memberIdTo member id
   * @return object object
   */
  public function getByFromAndTo($memberIdFrom, $memberIdTo)
  {
    return $this->createQuery()
      ->where('member_id_from = ?', $memberIdFrom)
      ->addWhere('member_id_to = ?', $memberIdTo)
      ->fetchOne();
  }

  /**
   * Get introFriend pager
   * @param int $memberIdTo member id
   * @param int $page page number
   * @param int $size show number
   * @return pager pager
   */
  public function getListPager($memberIdTo, $page = 1, $size = 20)
  {
    $this->deleteCheck($memberIdTo);
    $q = Doctrine::getTable('IntroFriend')->createQuery()
      ->where('member_id_to = ?', $memberIdTo)
      ->orderBy('id');

    $pager = new sfDoctrinePager('IntroFriend', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  /**
   * Get Count
   * @param int $member_id_to member id
   * @return int count
   */
  public function getCount($member_id_to)
  {
    $introFriends = $this->find($member_id_to);
    if (!$introFriends)
    {
      return 0;
    }

    return $introFriends->count();
  }

  /**
   * Get to member
   * @param array $introFriends IntroFriend array
   * @return array Member array
   */
  public function getWriters($introFriends)
  {
    $writers = array();
    foreach ($introFriends as $i => $introFriend) {
      $writers[$i] = $this->getTable('Member')->find($introFriend->getMemberIdFrom());
    }
    return $writers;
  }
}
