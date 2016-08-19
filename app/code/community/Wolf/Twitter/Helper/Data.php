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

class Wolf_Twitter_Helper_Data extends Mage_Core_Helper_Data
{
  /**
   * Path to store config if front-end output is enabled
   *
   * @var string
   */
  protected $xmlPathToDefaults = 'wolf_twitter/view/';

  /**
   * Checks whether the list of Tweets can be displayed in the frontend
   *
   * @param integer|string|Mage_Core_Model_Store $store
   * @return boolean
   */
  public function isEnabled($store = null)
  {
    return Mage::getStoreConfigFlag($this->xmlPathToDefaults.'enabled', $store);
  }

  /**
   * Reads the maximum number of tweets displayed
   *
   * @param integer|string|Mage_Core_Model_Store $store
   * @return integer
   */
  public function getMaxTweets($store = null)
  {
    return Mage::getStoreConfig($this->xmlPathToDefaults.'max_tweets', $store);
  }

  /**
   * Reads the hashtag of tweets displayed
   *
   * @param integer|string|Mage_Core_Model_Store $store
   * @return string
   */
  public function getHashTag($store = null)
  {
    return Mage::getStoreConfig($this->xmlPathToDefaults.'hashtag', $store);
  }

}
