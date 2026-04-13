<?php

    namespace Ridley\Objects\Calculator;

    class Quote {

        public $valid = false;
        public $nonStandard = false;
        public $routeOptions = [];
        public $errors = [];
        public $priceModel;
        public $penalties = [];
        public $unitPriceString;
        public $collateralPremiumString;
        public $volumeString;
        public $destinationString;
        public $collateralString;
        public $standardPriceString;
        public $standardPrice;
        public $rushPriceString;
        public $rushPrice;
        public $rushAllowed;
        public $useRush;
        public $standardExpiration;
        public $rushExpiration;
        public $standardTimeToComplete;
        public $rushTimeToComplete;

        function __construct(public $contractCorporation){}

    }

?>