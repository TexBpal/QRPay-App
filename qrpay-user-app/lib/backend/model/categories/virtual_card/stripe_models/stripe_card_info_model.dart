class StripeCardInfoModel {
  Message message;
  Data data;

  StripeCardInfoModel({
    required this.message,
    required this.data,
  });

  factory StripeCardInfoModel.fromJson(Map<String, dynamic> json) =>
      StripeCardInfoModel(
        message: Message.fromJson(json["message"]),
        data: Data.fromJson(json["data"]),
      );

  Map<String, dynamic> toJson() => {
        "message": message.toJson(),
        "data": data.toJson(),
      };
}

class Data {
  String baseCurr;
  CardBasicInfo cardBasicInfo;
  List<MyCard> myCard;
  List<UserWallet>? userWallet;
  CardCharge cardCharge;
  List<Transaction> transactions;

  Data({
    required this.baseCurr,
    required this.cardBasicInfo,
    required this.myCard,
    required this.userWallet,
    required this.cardCharge,
    required this.transactions,
  });

  factory Data.fromJson(Map<String, dynamic> json) => Data(
        baseCurr: json["base_curr"],
        cardBasicInfo: CardBasicInfo.fromJson(json["card_basic_info"]),
        myCard:
            List<MyCard>.from(json["myCard"].map((x) => MyCard.fromJson(x))),
       userWallet: (json['userWallet'] as List)
          .map((e) => UserWallet.fromJson(e))
          .toList(),
        cardCharge: CardCharge.fromJson(json["cardCharge"]),
        transactions: List<Transaction>.from(
            json["transactions"].map((x) => Transaction.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "base_curr": baseCurr,
        "card_basic_info": cardBasicInfo.toJson(),
        "myCard": List<dynamic>.from(myCard.map((x) => x.toJson())),
        "cardCharge": cardCharge.toJson(),
        "transactions": List<dynamic>.from(transactions.map((x) => x.toJson())),
      };
}

class CardBasicInfo {
  int cardCreateLimit;
  String cardBackDetails;
  String cardBg;
  String siteTitle;
  String siteLogo;
  String siteFav;

  CardBasicInfo({
    required this.cardCreateLimit,
    required this.cardBackDetails,
    required this.cardBg,
    required this.siteTitle,
    required this.siteLogo,
    required this.siteFav,
  });

  factory CardBasicInfo.fromJson(Map<String, dynamic> json) => CardBasicInfo(
        cardCreateLimit: json["card_create_limit"],
        cardBackDetails: json["card_back_details"],
        cardBg: json["card_bg"],
        siteTitle: json["site_title"],
        siteLogo: json["site_logo"],
        siteFav: json["site_fav"],
      );

  Map<String, dynamic> toJson() => {
        "card_create_limit": cardCreateLimit,
        "card_back_details": cardBackDetails,
        "card_bg": cardBg,
        "site_title": siteTitle,
        "site_logo": siteLogo,
        "site_fav": siteFav,
      };
}

class CardCharge {
  int id;
  String slug;
  String title;
  dynamic fixedCharge;
  dynamic percentCharge;
  dynamic minLimit;
  dynamic maxLimit;

  CardCharge({
    required this.id,
    required this.slug,
    required this.title,
    required this.fixedCharge,
    required this.percentCharge,
    required this.minLimit,
    required this.maxLimit,
  });

  factory CardCharge.fromJson(Map<String, dynamic> json) => CardCharge(
        id: json["id"],
        slug: json["slug"],
        title: json["title"],
        fixedCharge: json["fixed_charge"],
        percentCharge: json["percent_charge"],
        minLimit: json["min_limit"],
        maxLimit: json["max_limit"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "slug": slug,
        "title": title,
        "fixed_charge": fixedCharge,
        "percent_charge": percentCharge,
        "min_limit": minLimit,
        "max_limit": maxLimit,
      };
}

class MyCard {
  int id;
  String cardId;
  String currency;
  String cardHolder;
  String brand;
  String type;
  String cardPan;
  String expiryMonth;
  String expiryYear;
  String cvv;
  String cardBackDetails;
  String siteTitle;
  String siteLogo;
  bool status;
  MyCardStatusInfo statusInfo;

  MyCard({
    required this.id,
    required this.cardId,
    required this.currency,
    required this.cardHolder,
    required this.brand,
    required this.type,
    required this.cardPan,
    required this.expiryMonth,
    required this.expiryYear,
    required this.cvv,
    required this.cardBackDetails,
    required this.siteTitle,
    required this.siteLogo,
    required this.status,
    required this.statusInfo,
  });

  factory MyCard.fromJson(Map<String, dynamic> json) => MyCard(
        id: json["id"],
        cardId: json["card_id"],
        currency: json["currency"],
        cardHolder: json["card_holder"],
        brand: json["brand"],
        type: json["type"],
        cardPan: json["card_pan"],
        expiryMonth: json["expiry_month"],
        expiryYear: json["expiry_year"],
        cvv: json["cvv"],
        cardBackDetails: json["card_back_details"],
        siteTitle: json["site_title"],
        siteLogo: json["site_logo"],
        status: json["status"],
        statusInfo: MyCardStatusInfo.fromJson(json["status_info"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "card_id": cardId,
        "currency": currency,
        "card_holder": cardHolder,
        "brand": brand,
        "type": type,
        "card_pan": cardPan,
        "expiry_month": expiryMonth,
        "expiry_year": expiryYear,
        "cvv": cvv,
        "card_back_details": cardBackDetails,
        "site_title": siteTitle,
        "site_logo": siteLogo,
        "status": status,
        "status_info": statusInfo.toJson(),
      };
}

class MyCardStatusInfo {
  int active;
  int inactive;

  MyCardStatusInfo({
    required this.active,
    required this.inactive,
  });

  factory MyCardStatusInfo.fromJson(Map<String, dynamic> json) =>
      MyCardStatusInfo(
        active: json["active"],
        inactive: json["inactive"],
      );

  Map<String, dynamic> toJson() => {
        "active": active,
        "inactive": inactive,
      };
}

class Transaction {
  int id;
  String trx;
  String transactionType;
  String requestAmount;
  String payable;
  String totalCharge;
  String cardAmount;
  String cardNumber;
  String currentBalance;
  String status;
  DateTime dateTime;
  TransactionStatusInfo statusInfo;

  Transaction({
    required this.id,
    required this.trx,
    required this.transactionType,
    required this.requestAmount,
    required this.payable,
    required this.totalCharge,
    required this.cardAmount,
    required this.cardNumber,
    required this.currentBalance,
    required this.status,
    required this.dateTime,
    required this.statusInfo,
  });

  factory Transaction.fromJson(Map<String, dynamic> json) => Transaction(
        id: json["id"],
        trx: json["trx"],
        transactionType: json["transaction_type"],
        requestAmount: json["request_amount"],
        payable: json["payable"],
        totalCharge: json["total_charge"],
        cardAmount: json["card_amount"],
        cardNumber: json["card_number"],
        currentBalance: json["current_balance"],
        status: json["status"],
        dateTime: DateTime.parse(json["date_time"]),
        statusInfo: TransactionStatusInfo.fromJson(json["status_info"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "trx": trx,
        "transaction_type": transactionType,
        "request_amount": requestAmount,
        "payable": payable,
        "total_charge": totalCharge,
        "card_amount": cardAmount,
        "card_number": cardNumber,
        "current_balance": currentBalance,
        "status": status,
        "date_time": dateTime.toIso8601String(),
        "status_info": statusInfo.toJson(),
      };
}

class TransactionStatusInfo {
  int success;
  int pending;
  int rejected;

  TransactionStatusInfo({
    required this.success,
    required this.pending,
    required this.rejected,
  });

  factory TransactionStatusInfo.fromJson(Map<String, dynamic> json) =>
      TransactionStatusInfo(
        success: json["success"],
        pending: json["pending"],
        rejected: json["rejected"],
      );

  Map<String, dynamic> toJson() => {
        "success": success,
        "pending": pending,
        "rejected": rejected,
      };
}

class UserWallet {
  final double balance;
  final int status;
  final Currency currency;

  UserWallet({
    required this.balance,
    required this.status,
    required this.currency,
  });

  factory UserWallet.fromJson(Map<String, dynamic> json) {
    return UserWallet(
      balance: (json['balance'] as num).toDouble(),
      status: json['status'],
      currency: Currency.fromJson(json['currency']),
    );
  }
}

class Currency {
  final int id;
  final String code;
  final double rate;
  final String flag;
  final String symbol;
  final String type;
  final int? defaultCurrency;
  final String country;
  final String name;
  final bool both;
  final bool senderCurrency;
  final bool receiverCurrency;
  final String? editData;
  final String currencyImage;

  Currency({
    required this.id,
    required this.code,
    required this.rate,
    required this.flag,
    required this.symbol,
    required this.type,
    required this.defaultCurrency,
    required this.country,
    required this.name,
    required this.both,
    required this.senderCurrency,
    required this.receiverCurrency,
    required this.editData,
    required this.currencyImage,
  });

  factory Currency.fromJson(Map<String, dynamic> json) {
    return Currency(
      id: json['id'],
      code: json['code'],
      rate: (json['rate'] as num).toDouble(),
      flag: json['flag'],
      symbol: json['symbol'],
      type: json['type'],
      defaultCurrency: json['default'],
      country: json['country'],
      name: json['name'],
      both: json['both'],
      senderCurrency: json['senderCurrency'],
      receiverCurrency: json['receiverCurrency'],
      editData: json['editData'],
      currencyImage: json['currencyImage'],
    );
  }
}
class Message {
  List<String> success;

  Message({
    required this.success,
  });

  factory Message.fromJson(Map<String, dynamic> json) => Message(
        success: List<String>.from(json["success"].map((x) => x)),
      );

  Map<String, dynamic> toJson() => {
        "success": List<dynamic>.from(success.map((x) => x)),
      };
}
