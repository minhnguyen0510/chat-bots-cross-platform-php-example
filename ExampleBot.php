<?php

namespace ChatBot;

use ChatBot\Messages\ReceivedMessage;
use ChatBot\Messages\Message;
use ChatBot\Messages\ImageMessage;
use ChatBot\Messages\StructuredMessage;
use ChatBot\Messages\MessageButton;
use ChatBot\Messages\MessageReceiptElement;
use ChatBot\Messages\Address;
use ChatBot\Messages\Summary;
use ChatBot\Messages\Adjustment;

class ExampleBot extends BotApplication
{
    public function receive($platform, ReceivedMessage $message)
    {
        switch ($message->getText())
        {
            // When bot receive "text"
            case 'text':
                $this->send(new Message($message->getSender(), 'This is a simple text message.'));
            break;

            // When bot receive "image"
            case 'image':
                $this->send(new ImageMessage($message->getSender(), 'https://developers.facebook.com/images/devsite/fb4d_logo-2x.png'));
                break;

            // When bot receive "profile"
            case 'profile':
                $user = $this->userProfile($message->getSender());

                $this->send(new StructuredMessage($message->getSender(),
                    StructuredMessage::TYPE_GENERIC,
                    [
                        'elements' => [
                            new MessageElement($user->getFirstName()." ".$user->getLastName(), " ", $user->getPicture())
                        ]
                    ]
                ));
                break;
            // When bot receive "button"
            case 'button':
                $this->send(new StructuredMessage($message->getSender(),
                    StructuredMessage::TYPE_BUTTON,
                    [
                        'text' => 'Choose category',
                        'buttons' => [
                            new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                            new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button'),
                            new MessageButton(MessageButton::TYPE_POSTBACK, 'Third button')
                        ]
                    ]
                ));
                break;

            // When bot receive "items"
            case 'items':
                $this->send(new StructuredMessage($message->getSender(),
                    StructuredMessage::TYPE_GENERIC,
                    [
                        'elements' => [
                            new MessageElement("First item", "Item description", "", [
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                                new MessageButton(MessageButton::TYPE_WEB, 'Web link', 'http://facebook.com')
                            ]),
                            new MessageElement("Second item", "Item description", "", [
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button')
                            ]),
                            new MessageElement("Third item", "Item description", "", [
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button')
                            ])
                        ]
                    ]
                ));

                break;

            // When bot receive "receipt"
            case 'receipt':

                if ($platform == self::PLATFORM_FB) {
                    $this->send(new StructuredMessage($message->getSender(),
                        StructuredMessage::TYPE_RECEIPT,
                        [
                            'recipient_name' => 'Fox Brown',
                            'order_number' => rand(10000, 99999),
                            'currency' => 'USD',
                            'payment_method' => 'VISA',
                            'order_url' => 'http://facebook.com',
                            'timestamp' => time(),
                            'elements' => [
                                new MessageReceiptElement("First item", "Item description", "", 1, 300, "USD"),
                                new MessageReceiptElement("Second item", "Item description", "", 2, 200, "USD"),
                                new MessageReceiptElement("Third item", "Item description", "", 3, 1800, "USD"),
                            ],
                            'address' => new Address([
                                'country' => 'US',
                                'state' => 'CA',
                                'postal_code' => 94025,
                                'city' => 'Menlo Park',
                                'street_1' => '1 Hacker Way',
                                'street_2' => ''
                            ]),
                            'summary' => new Summary([
                                'subtotal' => 2300,
                                'shipping_cost' => 150,
                                'total_tax' => 50,
                                'total_cost' => 2500,
                            ]),
                            'adjustments' => [
                                new Adjustment([
                                    'name' => 'New Customer Discount',
                                    'amount' => 20
                                ]),
                                new Adjustment([
                                    'name' => '$10 Off Coupon',
                                    'amount' => 10
                                ])
                            ]
                        ]
                    ));
                }

            break;

            // Other message received
            default:
                $this->send(new Message($message->getSender(), 'Sorry. I don’t understand you.'));
        }
    }
}