<?php
namespace MyCustomModules\CustomNewsletter\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Zend\Log\Filter\Timestamp;
 
class Post extends \Magento\Framework\App\Action\Action
{
    protected $_logLoggerInterface;
    protected $subscriberFactory;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Psr\Log\LoggerInterface $loggerInterface,
        array $data = []
        )
    {
        $this->_logLoggerInterface = $loggerInterface;
        $this->subscriberFactory= $subscriberFactory;
        $this->messageManager = $context->getMessageManager();
        parent::__construct($context);  
    }
     
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $email = $post['email'];
        try
        {
           $this->subscriberFactory->create()->subscribe($email);
           $coupon_code = mt_rand(100000, 999999);

           // SEND EMAIL WITH ABOVE COUPON CODE HERE
           
           $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status'  => "ok",
                    'message' => 'Subscriber added successfully. Coupon Code: ' .$coupon_code
                ]);

            return $response;
           exit; 
        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
            exit;
        }   
    }
}