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

namespace Belsignum\PaypalSubscription\Controller\Backend;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use Belsignum\PaypalSubscription\Domain\Model\Product\Product;
use Belsignum\PaypalSubscription\Utility\SubscriptionUtility;
use Extcode\CartProducts\Domain\Repository\Product\ProductRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Belsignum\PaypalSubscription\Domain\Repository\Order;

class SubscriptionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	/**
	 * @var \Belsignum\PaypalSubscription\Utility\SubscriptionUtility
	 */
	protected $subscriptionUtility;

	/**
	 * @var \Extcode\CartProducts\Domain\Repository\Product\ProductRepository
	 */
	protected $productRepository;

	/**
	 * @var Order\ItemRepository
	 */
	protected $orderItemRepository;

	/**
	 * initialize action
	 * @return void
	 */
	public function initializeAction():void
	{
		$this->subscriptionUtility = new SubscriptionUtility(
			$this->settings
		);
		$this->productRepository = $this->objectManager->get(
			ProductRepository::class
		);
		$this->orderItemRepository = $this->objectManager->get(
			Order\ItemRepository::class
		);
	}

	/**
	 * product action
	 * @return void
	 */
	public function productAction():void
	{
		$pageId = (int) GeneralUtility::_GP('id');
		$products = $this->productRepository->findByIsSubscription(TRUE);
		$this->view->assignMultiple([
			'products' => $products,
			'returnUrl' => rawurlencode(
				BackendUtility::getModuleUrl(
					'Cart_PaypalSubscriptionPlans',
					['id' => $pageId]
				)
			)
		]);
	}

	/**
	 * update action
	 *
	 * @param \Belsignum\PaypalSubscription\Domain\Model\Product\Product $product
	 * @param string                                                     $operation
	 *
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
	 */
	public function updateAction(Product $product, string $operation):void
	{
		if($operation === 'createProduct')
		{
			$response = $this->subscriptionUtility->createCatalogProduct($product);
			$product->setPaypalProductId($response->id);
			$this->productRepository->update($product);
		}

		if($operation === 'createPlan')
		{
			$response = $this->subscriptionUtility->createBillingPlan($product);
			$product->setPaypalPlanId($response->id);
			$this->productRepository->update($product);
		}

		$this->redirect('product');
	}

	/**
	 * Action list
	 *
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
	 * @return void
	 */
	public function listAction():void
	{
		$pageId = (int) GeneralUtility::_GP('id');
		$subscriptions = $this->orderItemRepository->findSubsctiptionOrders();
		$this->view->assignMultiple([
			'subscriptions' => $subscriptions,
			'returnUrl' => rawurlencode(
				BackendUtility::getModuleUrl(
					'Cart_PaypalSubscriptionSubscriptions',
					['id' => $pageId]
				)
			)
		]);
	}
}
