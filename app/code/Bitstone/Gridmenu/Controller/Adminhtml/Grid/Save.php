<?php


namespace Bitstone\Gridmenu\Controller\Adminhtml\Grid;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Bitstone\Gridmenu\Model\GridFactory
     */
    var $gridFactory;
    protected $uploaderFactory;
    protected $mediaDirectory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Bitstone\Gridmenu\Model\GridFactory $gridFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context  $context,
        \Bitstone\Gridmenu\Model\GridFactory $gridFactory
    )
    {

        parent::__construct($context);
        $this->gridFactory = $gridFactory;


    }

    private function processImage($data, $model)
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();


        if ($data) {
            $model = $this->_objectManager->create('Bitstone\Gridmenu\Model\Grid');
            $postId = $this->getRequest()->getParam('entity_id');

            if ($postId) {
                $model->load($postId);
            }
            $formData = $this->getRequest()->getParam('label');
            if (!empty($post['image']['value'])) {
                $imageName = $post['image']['value'];
                $post['image'] = $imageName;
            }

            $imagePost = $this->getRequest()->getFiles('image');
            $fileName = ($imagePost && array_key_exists('name', $imagePost)) ? $imagePost['name'] : null;
            if ($imagePost && $fileName) {
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $imageAdapterFactory = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')
                        ->create();
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);

                    $result = $uploader->save(
                        $mediaDirectory
                            ->getAbsolutePath('bitstone/image')
                    );
                    $model->setImage('bitstone/image' . $result['file']);


                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            }

        }
        try {


            // var_dump($model->setProimage( $prev_img ));exit;  // before saving


            $model->save();

            // Display success message
            $this->messageManager->addSuccess(__('Image has been successfully saved.'));

            // Check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/addrow', ['id' => $model->getId(), '_current' => true]);
                return;
            }

            // Go to grid page
            $this->_redirect('grid/grid/index');
            return;
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->_getSession()->setFormData($formData);
        $this->_redirect('*/*/addrow', ['id' => $postId]);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('grid/grid/addrow');
            return;
        }
        try {
            $rowData = $this->gridFactory->create();
            if (isset($data['entity_id'])) {
                $date = array('entity_id' => $data['entity_id'], 'title' => $data['title'], 'subtitle' => $data['subtitle'], 'content' => $data['content'], 'publish_date' => $data['publish_date'], 'is_active' => $data['is_active']);
                $rowData->setData($date);
                $this->processImage($data, $rowData);
                $rowData->save();

            } else {
                $date = array('title' => $data['title'], 'subtitle' => $data['subtitle'], 'content' => $data['content'], 'publish_date' => $data['publish_date'], 'is_active' => $data['is_active']);

                $rowData->setData($date);
                //$this->processImage($data, $rowData);
                $rowData->save();
            }
            //$rowData->setData($data['title']);



            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('grid/grid/index');
    }

    //Old execute()
    /**
     *  if (!$data) {
     * $this->_redirect('grid/grid/addrow');
     * return;
     * }
     * try {
     * $rowData = $this->gridFactory->create();
     * $rowData->setData($data);
     * if (isset($data['id'])) {
     * $rowData->setEntityId($data['id']);
     * }
     * $rowData->save();
     * //$this->processImage($data, $rowData);
     * $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
     * } catch (\Exception $e) {
     * $this->messageManager->addError(__($e->getMessage()));
     * }
     * $this->_redirect('grid/grid/index');
     */


    /**
     * @return bool
     */
    protected
    function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bitstone_Gridmenu::save');
    }
}
