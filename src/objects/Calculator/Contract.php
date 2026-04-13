<?php

    namespace Ridley\Objects\Calculator;

    class Contract {

        public $status;

        public $issuerID;
        public $issuerData;
        public $acceptorID;
        public $acceptorData;

        public $startLocationID;
        public $startLocationType;
        public $startLocation;
        public $startSystemID;
        public $startSystem;

        public $endLocationID;
        public $endLocationType;
        public $endLocation;
        public $endSystemID;
        public $endSystem;

        public $collateral;
        public $reward;
        public $volume;

        public $issueDate;
        public $expiration;
        public $expirationDays;
        public $daysToComplete;

        public $quote;
        public $expectedExpirationDays;
        public $expectedDaysToComplete;
        public $expectedReward;
        public $isRush;
        public $criticalIssues = [];
        public $issues = [];

        public $standing;

        function __construct(private $contractData) {

            $this->status = $contractData["status"];

            if ($contractData["acceptor_id"] != 0) {
                $this->acceptorID = $contractData["acceptor_id"];
            }
            $this->issuerID = $contractData["issuer_id"];

            $this->startLocationID = $contractData["start_location_id"];
            $this->startLocationType = ($this->startLocationID >= 60000000 and $this->startLocationID <= 64000000) ? "Station" : "Citadel";
            $this->endLocationID = $contractData["end_location_id"];
            $this->endLocationType = ($this->endLocationID >= 60000000 and $this->endLocationID <= 64000000) ? "Station" : "Citadel";

            $this->collateral = $contractData["collateral"];
            $this->reward = $contractData["reward"];
            $this->volume = $contractData["volume"];

            $this->issueDate = new \DateTimeImmutable($contractData["date_issued"]);
            $this->expiration = new \DateTimeImmutable($contractData["date_expired"]);
            $this->expirationDays = $this->expiration->diff($this->issueDate)->days;
            $this->daysToComplete = $contractData["days_to_complete"];

        }

        public function build_issues_and_standing() {

            $this->criticalIssues = $this->quote?->errors ?? ["Error! Start or End Location Inaccessible."];

            if (empty($this->criticalIssues)) {

                $this->isRush = ($this->expirationDays < $this->quote->standardExpiration or $this->daysToComplete < $this->quote->standardTimeToComplete);
                $withinRush = ($this->isRush and $this->expirationDays >= $this->quote->rushExpiration and $this->daysToComplete >= $this->quote->rushTimeToComplete);

                // Set Expected Values
                if ($this->isRush and $this->quote->rushAllowed) {
                    $this->expectedExpirationDays = $this->quote->rushExpiration;
                    $this->expectedDaysToComplete = $this->quote->rushTimeToComplete;
                    $this->expectedReward = $this->quote->rushPrice;
                }
                else {
                    $this->expectedExpirationDays = $this->quote->standardExpiration;
                    $this->expectedDaysToComplete = $this->quote->standardTimeToComplete;
                    $this->expectedReward = $this->quote->standardPrice;
                }

                // Check for Issues
                if ($this->isRush and !$this->quote->rushAllowed and $withinRush) {
                    $this->issues[] = "Rush Not Allowed";
                }
                elseif ($this->expirationDays < $this->expectedExpirationDays or $this->daysToComplete < $this->expectedDaysToComplete) {
                    $this->issues[] = "Expiration or Time to Complete Too Short";
                }
                if ($this->reward < $this->expectedReward) {
                    $this->issues[] = "Reward Too Low";
                }

            }

            if (!empty($this->criticalIssues)) {
                $this->standing = "danger";
            }
            elseif (!empty($this->issues)) {
                $this->standing = "warning";
            }
            elseif ($this->reward > $this->expectedReward) {
                $this->standing = "success";
            }
            else {
                $this->standing = "primary";
            }

        }

    }

?>