<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * introfriend actions.
 *
 * @package    OpenPNE
 * @subpackage introfriend
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class introfriendActions extends opIntroFriendPluginIntroFriendActions 
{
 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->introList = IntroFriendPeer::getByTo($this->id);
    if(!count($this->introList)) { return sfView::ERROR; }
  }
}
