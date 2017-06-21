<?php
/**
 * Fastly CDN for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Fastly CDN for Magento End User License Agreement
 * that is bundled with this package in the file LICENSE_FASTLY_CDN.txt.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Fastly CDN to newer
 * versions in the future. If you wish to customize this module for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Fastly
 * @package     Fastly_Cdn
 * @copyright   Copyright (c) 2016 Fastly, Inc. (http://www.fastly.com)
 * @license     BSD, see LICENSE_FASTLY_CDN.txt
 */
namespace Fastly\Cdn\Controller\Adminhtml\FastlyCdn\Purge;

use Fastly\Cdn\Model\PurgeCache;
use Fastly\Cdn\Model\Config;
use Magento\Framework\App\ResponseInterface;

class ContentType extends \Magento\Backend\App\Action
{
    /**
     * @var PurgeCache
     */
    protected $purgeCache;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param PurgeCache $purgeCache
     * @param Config $config
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PurgeCache $purgeCache,
        Config $config
    ) {
        parent::__construct($context);
        $this->purgeCache = $purgeCache;
        $this->config = $config;
    }

    /**
     * Purge by content type
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        try {
            if ($this->config->getType() == Config::FASTLY && $this->config->isEnabled()) {
                // check if content type is given
                $contentType = $this->getRequest()->getParam('content_types', false);
                if (!$contentType) {
                    throw new \Exception(__('Invalid content type "'.$contentType.'".'));
                }

                $result = $this->purgeCache->sendPurgeRequest([$contentType]);

                if ($result) {
                    $this->getMessageManager()->addSuccessMessage(__('The Fastly CDN has been cleaned.'));
                } else {
                    $this->getMessageManager()->addErrorMessage(
                        __('The purge request was not processed successfully.')
                    );
                }
            }
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage(
                __('An error occurred while clearing the Fastly CDN: ') . $e->getMessage()
            );
        }
        return $this->_redirect('*/cache/index');
    }
}
