<?php

namespace App\DataFixtures;

use App\Entity\Airdrop;
use App\Entity\AirdropRecipient;
use App\Entity\Member;
use App\Entity\Recipient;
use App\Entity\Token;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $tokenJson = file_get_contents(__DIR__ . './../../../infra/testdata/token.json');
    $dropsJson = file_get_contents(__DIR__ . './../../../infra/testdata/drops.json');

    $address = json_decode($tokenJson);
    $drops = json_decode($dropsJson);

    $token = new Token();
    $token->setAddress($address);
    $token->setNetwork('local');
    $token->setIdentifier(0);
    $token->setCreated(new DateTime());

    $manager->persist($token);

    $airdrop = new Airdrop();
    $airdrop->setToken($token);
    $airdrop->setName('Test Airdrop #1');
    $airdrop->setCreated(new DateTime());

    $manager->persist($airdrop);

    $addresses = [
      'tz1baLSnTXirZwSqbH6LJf136JhP4J1FpvEG',
      'tz1dmn3QEzmVwtuf72B1bhsuA9uL8NYoRwxq',
      'tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb',
      'tz1aSkwEot3L2kmUvcoxzjMomb9mvBNuzFK6',
    ];

    foreach ($addresses as $address) {
      $member = new Member();
      $member->setAddress($address);
      $member->setCreated(new DateTime());
      $manager->persist($member);
    }

    foreach ($drops as $drop) {
      $recipient = new Recipient();
      $recipient->setAddress($drop->pkh);
      $recipient->setCreated(new DateTime());

      $airdropRecipient = new AirdropRecipient();
      $airdropRecipient->setAmount($drop->amount);
      $airdropRecipient->setAirdrop($airdrop);
      $airdropRecipient->setRecipient($recipient);

      $manager->persist($recipient);
      $manager->persist($airdropRecipient);
    }

    $manager->flush();
  }
}
