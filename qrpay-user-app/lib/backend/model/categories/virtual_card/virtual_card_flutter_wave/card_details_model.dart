import 'dart:convert';

CardDetailsModel cardDetailsModelFromJson(String str) =>
    CardDetailsModel.fromJson(json.decode(str));

String cardDetailsModelToJson(CardDetailsModel data) =>
    json.encode(data.toJson());

class CardDetailsModel {
  CardDetailsModel({
    required this.message,
    required this.data,
  });

  final Message message;
  final Data data;

  factory CardDetailsModel.fromJson(Map<String, dynamic> json) =>
      CardDetailsModel(
        message: Message.fromJson(json["message"]),
        data: Data.fromJson(json["data"]),
      );

  Map<String, dynamic> toJson() => {
        "message": message.toJson(),
        "data": data.toJson(),
      };
}

class Data {
  Data({
    required this.baseCurr,
    required this.myCards,
  });

  final String baseCurr;
  final MyCards myCards;

  factory Data.fromJson(Map<String, dynamic> json) => Data(
        baseCurr: json["base_curr"],
        myCards: MyCards.fromJson(json["myCards"]),
      );

  Map<String, dynamic> toJson() => {
        "base_curr": baseCurr,
        "myCards": myCards.toJson(),
      };
}

class MyCards {
  MyCards({
    required this.id,
    required this.name,
    required this.accountId,
    required this.cardId,
    required this.cardHash,
    required this.cardPan,
    required this.maskedCard,
    required this.expiration,
    required this.cvv,
    required this.cardType,
    required this.city,
    required this.state,
    required this.zipCode,
    required this.address,
    required this.amount,
    required this.cardBackDetails,
    required this.siteTitle,
    required this.siteLogo,
    required this.status,
    required this.statusInfo,
  });

  final int id;
  final String name;
  final String accountId;
  final String cardId;
  final String cardHash;
  final String cardPan;
  final String maskedCard;
  final String expiration;
  final String cvv;
  final String cardType;
  final String city;
  final String state;
  final String zipCode;
  final String address;
  final dynamic amount;
  final dynamic cardBackDetails;
  final String siteTitle;
  final String siteLogo;
  final int status;
  final StatusInfo statusInfo;

  factory MyCards.fromJson(Map<String, dynamic> json) => MyCards(
        id: json["id"],
        name: json["name"],
        accountId: json["account_id"],
        cardId: json["card_id"],
        cardHash: json["card_hash"],
        cardPan: json["card_pan"],
        maskedCard: json["masked_card"],
        expiration: json["expiration"],
        cvv: json["cvv"],
        cardType: json["card_type"],
        city: json["city"],
        state: json["state"],
        zipCode: json["zip_code"],
        address: json["address"],
        amount: json["amount"]?.toDouble() ?? 0.0,
        cardBackDetails: json["card_back_details"] ?? '',
        siteTitle: json["site_title"],
        siteLogo: json["site_logo"],
        status: json["status"],
        statusInfo: StatusInfo.fromJson(json["status_info"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "account_id": accountId,
        "card_id": cardId,
        "card_hash": cardHash,
        "card_pan": cardPan,
        "masked_card": maskedCard,
        "expiration": expiration,
        "cvv": cvv,
        "card_type": cardType,
        "city": city,
        "state": state,
        "zip_code": zipCode,
        "address": address,
        "amount": amount,
        "card_back_details": cardBackDetails,
        "site_title": siteTitle,
        "site_logo": siteLogo,
        "status": status,
        "status_info": statusInfo.toJson(),
      };
}

class StatusInfo {
  StatusInfo({
    required this.block,
    required this.unblock,
  });

  final int block;
  final int unblock;

  factory StatusInfo.fromJson(Map<String, dynamic> json) => StatusInfo(
        block: json["block"],
        unblock: json["unblock"],
      );

  Map<String, dynamic> toJson() => {
        "block": block,
        "unblock": unblock,
      };
}

class Message {
  Message({
    required this.success,
  });

  final List<String> success;

  factory Message.fromJson(Map<String, dynamic> json) => Message(
        success: List<String>.from(json["success"].map((x) => x)),
      );

  Map<String, dynamic> toJson() => {
        "success": List<dynamic>.from(success.map((x) => x)),
      };
}
