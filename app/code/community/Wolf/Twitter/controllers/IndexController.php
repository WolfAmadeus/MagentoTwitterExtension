<?php

/* 
 * Copyright (C) 2016 Wolfgang Lorenz <wolfgang.a.lorenz@arcor.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Wolf_Twitter_IndexController extends Mage_Core_Controller_Front_Action
{

  public function preDispatch()
  {
    parent::preDispatch();
    if ( !Mage::helper('wolf_twitter')->isEnabled() ) {
      $this->setFlag('', 'no-dispatch', true);
      $this->_redirect('noRoute');
    }

  }

  public function indexAction()
  {
    $this->loadLayout();
    $this->renderLayout();
  }

}