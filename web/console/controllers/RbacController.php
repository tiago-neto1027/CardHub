<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $buyer = $auth->createRole('buyer');
        $auth->add($buyer);
        $seller = $auth->createRole('seller');
        $auth->add($seller);

        //Users
        // Add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a user';
        $auth->add($createUser);

        // Add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user details';
        $auth->add($updateUser);

        // Add "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete a user';
        $auth->add($deleteUser);

        // Add "manageRoles" permission
        $manageRoles = $auth->createPermission('manageRoles');
        $manageRoles->description = 'Manage roles and permissions of user';
        $auth->add($manageRoles);

        //Cards
        // Add "createCard" permission
        $createCard = $auth->createPermission('createCard');
        $createCard->description = 'Create a card';
        $auth->add($createCard);

        // Add "updateCard" permission
        $updateCard = $auth->createPermission('updateCard');
        $updateCard->description = 'Update a card';
        $auth->add($updateCard);

        // Add "deleteCard" permission
        $deleteCard = $auth->createPermission('deleteCard');
        $deleteCard->description = 'Delete a card';
        $auth->add($deleteCard);

        //Favorites
        // Add "viewFavorites" permission
        $viewFavorites = $auth->createPermission('viewFavorites');
        $viewFavorites->description = 'View favorite list';
        $auth->add($viewFavorites);

        //Listing
        // Add "createListing" permission
        $createListing = $auth->createPermission('createListing');
        $createListing->description = 'Add an item to listing';
        $auth->add($createListing);

        // Add "deleteListing" permission
        $deleteListing = $auth->createPermission('deleteListing');
        $deleteListing->description = 'Remove an item from listing';
        $auth->add($deleteListing);

        // Add "updateListing" permission
        $updateListing = $auth->createPermission('updateListing');
        $updateListing->description = 'Update an item from listing';
        $auth->add($updateListing);

        // Add "viewListing" permission
        $viewListing = $auth->createPermission('viewListing');
        $viewListing->description = 'View the items on listing';
        $auth->add($viewListing);

        //Products
        // Add "createProduct" permission
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Create a product';
        $auth->add($createProduct);

        // Add "updateProduct" permission
        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Update a product';
        $auth->add($updateProduct);

        // Add "deleteProduct" permission
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Delete a product';
        $auth->add($deleteProduct);


        //Games
        // Add "createGame" permission
        $createGame = $auth->createPermission('createGame');
        $createGame->description = 'Create a  game';
        $auth->add($createGame);

        // Add "updateGame" permission
        $updateGame = $auth->createPermission('updateGame');
        $updateGame->description = 'Update a game';
        $auth->add($updateGame);

        // Add "deleteGame" permission
        $deleteGame = $auth->createPermission('deleteGame');
        $deleteGame->description = 'Delete a game';
        $auth->add($deleteGame);


        //productReview
        // Add "createProductReview" permission
        $createProductReview = $auth->createPermission('createProductReview');
        $createProductReview->description = 'Create a product review';
        $auth->add($createProductReview);

        // Add "updateProductReview" permission
        $updateProductReview = $auth->createPermission('$updateProductReview');
        $updateProductReview->description = 'Update a product review';
        $auth->add($updateProductReview);

        // Add "deleteProductReview" permission
        $deleteProductReview = $auth->createPermission('deleteProductReview');
        $deleteProductReview->description = 'Delete a product review';
        $auth->add($deleteProductReview);


        //CardReview
        // Add "createCardReview" permission
        $createCardReview = $auth->createPermission('createCardReview');
        $createCardReview->description = 'Create a card review';
        $auth->add($createCardReview);

        // Add "updateProductReview" permission
        $updateCardReview = $auth->createPermission('$updateCardReview');
        $updateCardReview->description = 'Update a card review';
        $auth->add($updateCardReview);

        // Add "deleteCardReview" permission
        $deleteCardReview = $auth->createPermission('deleteCardReview');
        $deleteCardReview->description = 'Delete a card review';
        $auth->add($deleteCardReview);


        //Reports
        // Add "createReport" permission
        $createReport = $auth->createPermission('createReport');
        $createReport->description = 'Create a report';
        $auth->add($createReport);

        // Add "updateReport" permission
        $updateReport = $auth->createPermission('updateReport');
        $updateReport->description = 'Update a report';
        $auth->add($updateReport);

        // Add "deleteReport" permission
        $deleteReport = $auth->createPermission('deleteReport');
        $deleteReport->description = 'Delete a report';
        $auth->add($deleteReport);

        // Add "viewReport" permission
        $viewReport = $auth->createPermission('viewReport');
        $viewReport->description = 'View the details of a report';
        $auth->add($viewReport);


        //cardTransaction
        // Add "viewCardTransaction" permission
        $viewCardTransaction = $auth->createPermission('viewCardTransaction');
        $viewCardTransaction->description = 'View the details of a card transaction';
        $auth->add($viewCardTransaction);


        //productTransaction
        // Add "viewProductTransaction" permission
        $viewProductTransaction = $auth->createPermission('productTransaction');
        $viewProductTransaction->description = 'View the details of a product Transaction';
        $auth->add($viewProductTransaction);


        //Invoice
        // Add "deleteInvoice" permission
        $deleteInvoice = $auth->createPermission('deleteInvoice');
        $deleteInvoice->description = 'Delete a Invoice';
        $auth->add($deleteInvoice);

        // Add "viewInvoice" permission
        $viewInvoice = $auth->createPermission('viewInvoice');
        $viewInvoice->description = 'View the details of a Invoice';
        $auth->add($viewInvoice);

        //Payment
        // Add "createPayment" permission
        $createPayment = $auth->createPermission('createPayment');
        $createPayment->description = 'Create a Payment';
        $auth->add($createPayment);

        // Add "deletePayment" permission
        $deletePayment = $auth->createPermission('deletePayment');
        $deletePayment->description = 'Delete a payment';
        $auth->add($deletePayment);

        // Add "viewPayment" permission
        $viewPayment = $auth->createPermission('viewPayment');
        $viewPayment->description = 'View the details of a Payment';
        $auth->add($viewPayment);

        //Buyer permission
        //Users
        $auth->addChild($buyer, $createUser);
        $auth->addChild($buyer, $updateUser);
        $auth->addChild($buyer, $deleteUser);

        //Cards - The buyer doesn't have any specific card permissions (only view)

        //Favorites
        $auth->addChild($buyer, $viewFavorites);

        //Listing
        $auth->addChild($buyer, $viewListing);

        //Products - The buyer doesn't have any specific product permissions (only view)

        //Games - The buyer doesn't have any specific games permissions (only view)

        //cardReview
        $auth->addChild($buyer, $createCardReview);
        $auth->addChild($buyer, $deleteCardReview);
        $auth->addChild($buyer, $updateCardReview);

        //productReview
        $auth->addChild($buyer, $createProductReview);
        $auth->addChild($buyer, $deleteProductReview);
        $auth->addChild($buyer, $updateProductReview);

        //Reports
        $auth->addChild($buyer, $createReport);
        $auth->addChild($buyer, $deleteReport);
        $auth->addChild($buyer, $updateReport);
        $auth->addChild($buyer, $viewReport);

        //cardTransaction
        $auth->addChild($buyer, $viewCardTransaction);

        //productTransaction
        $auth->addChild($buyer, $viewProductTransaction);

        //Invoice
        $auth->addChild($buyer, $viewInvoice);

        //Payment
        $auth->addChild($buyer, $createPayment);
        $auth->addChild($buyer, $deletePayment);
        $auth->addChild($buyer, $viewPayment);


        //Seller permissions
        //getting all the buyer permissions
        $auth->addChild($seller, $buyer);

        //Card
        $auth->addChild($seller, $createCard);

        //Listing
        $auth->addChild($seller, $createListing);
        $auth->addChild($seller, $deleteListing);
        $auth->addChild($seller, $updateListing);


        //Admin permissions
        //getting all the seller permissions
        $auth->addChild($manager, $seller);

        //Cards
        $auth->addChild($manager, $createCard);
        $auth->addChild($manager, $deleteCard);

        //Games
        $auth->addChild($manager, $createGame);
        $auth->addChild($manager, $deleteGame);
        $auth->addChild($manager, $updateGame);

        //Products
        $auth->addChild($manager, $createProduct);
        $auth->addChild($manager, $deleteProduct);
        $auth->addChild($manager, $updateProduct);

        //Invoice
        $auth->addChild($manager, $deleteInvoice);


        //Manager permissions
        //getting all the seller permissions
        $auth->addChild($admin, $manager);

        //Users
        $auth->addChild($admin, $manageRoles);

        //
    }
}