<?php
/**
 * Typo3 Extension paypal_subscription
 * PayPal Subscriptions based on extensions cart and cart_products to enable recurring transactions
 * Copyright (C) 2019  Andreas Sommer <sommer@belsignum.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace Belsignum\PaypalSubscription\Domain\Model\Order;

class Item extends \Extcode\Cart\Domain\Model\Order\Item
{
    /**
     * Paypal Subscription ID
     *
     * @var string
     */
    protected $paypalSubscriptionId;

	/**
	 * @return string
	 */
	public function getPaypalSubscriptionId(): string
	{
		return $this->paypalSubscriptionId;
	}

	/**
	 * @param string $paypalSubscriptionId
	 */
	public function setPaypalSubscriptionId(string $paypalSubscriptionId): void
	{
		$this->paypalSubscriptionId = $paypalSubscriptionId;
	}
}
