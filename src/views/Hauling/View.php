<?php

    namespace Ridley\Views\Hauling;

    class Templates {
        
        protected function mainTemplate() {

            if (isset($this->controller->sourceCharacterID)) {
                ?>

                    <h3 class="text-light" id="contract-list-header">Contracts to Complete</h3>
                
                    <table class="table table-dark align-middle text-start text-wrap small mt-4">
                        <thead class="p-4">
                            <tr class="align-middle">
                                <th scope="col" style="width: 5%;">Status</th>
                                <th scope="col" style="width: 10%;">Issued</th>
                                <th scope="col" style="width: 5%;">Expires<br>(Expected)</th>
                                <th scope="col" style="width: 5%;">TTC<br>(Expected)</th>
                                <th scope="col" style="width: 20%;">Pickup<br>Drop-Off</th>
                                <th scope="col" style="width: 27.5%;">Issued By<br>Accepted By</th>
                                <th scope="col" style="width: 5%;">Volume</th>
                                <th scope="col" style="width: 7.5%;">Collateral</th>
                                <th scope="col" style="width: 10%;">Reward<br>(Expected)</th>
                                <th scope="col" style="width: 5%;">Problems</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $this->contractLister(); ?>

                        </tbody>
                    </table>
                
                <?php
            }
            else {
                ?>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="alert alert-warning text-center">
                            <h4 class="alert-heading">No Source Character Selected!</h4>
                            <hr>
                            <p>Contact your site administrator to select a source character. You can login a character below to add them as an option.</p>
                            <hr>
                            <a href="hauling/?action=login">
                                <img class="login-button" src="/resources/images/sso_image_dark.png">
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php
            }

        }

        protected function contractLister() {
            foreach ($this->model->contractData as $eachContractID => $eachContract) {
            ?>

            <tr class="table-<?php echo htmlspecialchars($eachContract->standing); ?>">
                <td><?php echo htmlspecialchars(ucwords(str_replace("_", " ", $eachContract->status))); ?></td>
                <td><?php echo htmlspecialchars($eachContract->issueDate->format("Y-m-d H:i:s \E\V\E")); ?></td>
                <td>
                    <?php echo htmlspecialchars($eachContract->expirationDays . " Days"); ?>
                    <?php if (isset($eachContract->expectedExpirationDays) and $eachContract->expectedExpirationDays != $eachContract->expirationDays) {?>
                        <br>
                        <?php echo htmlspecialchars("(" . $eachContract->expectedExpirationDays . " Days)"); ?>
                    <?php }?>
                </td>
                <td>
                    <?php echo htmlspecialchars($eachContract->daysToComplete . " Days"); ?>
                    <?php if (isset($eachContract->expectedDaysToComplete) and $eachContract->expectedDaysToComplete != $eachContract->daysToComplete) {?>
                        <br>
                        <?php echo htmlspecialchars("(" . $eachContract->expectedDaysToComplete . " Days)"); ?>
                    <?php }?>
                </td>
                <td>
                    <?php echo htmlspecialchars($eachContract->startLocation ?? "Unknown Location"); ?>
                    <br>
                    <?php echo htmlspecialchars($eachContract->endLocation ?? "Unknown Location"); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($eachContract->issuerData["Character Name"]); ?> 
                    <?php echo htmlspecialchars(" (" . $eachContract->issuerData["Corporation Name"] . ")"); ?>
                    <?php echo isset($eachContract->issuerData["Alliance Name"]) ? htmlspecialchars(" [" . $eachContract->issuerData["Alliance Name"] . "]") : ""; ?>
                    <?php if (isset($eachContract->acceptorData)) {?>
                        <br>
                        <?php echo htmlspecialchars($eachContract->acceptorData["Character Name"]); ?> 
                        <?php echo htmlspecialchars(" (" . $eachContract->acceptorData["Corporation Name"] . ")"); ?>
                        <?php echo isset($eachContract->acceptorData["Alliance Name"]) ? htmlspecialchars(" [" . $eachContract->acceptorData["Alliance Name"] . "]") : ""; ?>
                    <?php }?>
                </td>
                <td><?php echo htmlspecialchars(number_format($eachContract->volume) . " m³"); ?></td>
                <td><?php echo htmlspecialchars(number_format($eachContract->collateral) . " ISK"); ?></td>
                <td>
                    <?php echo htmlspecialchars(number_format($eachContract->reward) . " ISK"); ?>
                    <?php if (isset($eachContract->expectedReward) and $eachContract->expectedReward != $eachContract->reward) {?>
                        <br>
                        <?php echo htmlspecialchars("(" . number_format($eachContract->expectedReward) . " ISK)"); ?>
                    <?php }?>
                </td>
                <td>
                    <?php if (!empty($eachContract->criticalIssues)) {?>
                        <a class="issues-popover text-dark"  tabindex="0" data-bs-toggle="popover" data-bs-placement="left" data-bs-html="true" title="Critical Contract Issues" data-bs-content="<ul class='m-0 p-2'><li><?php echo implode("</li><li>", $eachContract->criticalIssues); ?></li></ul>"><i class="bi bi-x-octagon"></i></a><br>
                    <?php }
                    elseif (!empty($eachContract->issues)) {?>
                        <a class="issues-popover text-dark"  tabindex="0" data-bs-toggle="popover" data-bs-placement="left" data-bs-html="true" title="Contract Issues" data-bs-content="<ul class='m-0 p-2'><li><?php echo implode("</li><li>", $eachContract->issues); ?></li></ul>"><i class="bi bi-exclamation-triangle"></i></a><br>
                    <?php }?>
                </td>
            </tr>
                
            <?php
            }
        }
        
        protected function metaTemplate() {
            ?>
            
            <title>Hauling Dashboard</title>

            <script src="/resources/js/Hauling.js"></script>
            
            <?php
        }
        
        protected function styleTemplate() {
            ?>
            
            .issues-popover {
                border-bottom: dotted 1px;
            }

            .issues-popover {
                cursor: pointer;
            }
            
            <?php
        }

    }

    class View extends Templates implements \Ridley\Interfaces\View {
        
        protected $model;
        protected $controller;

        public function __construct(
            private \Ridley\Core\Dependencies\DependencyManager $dependencies
        ) {
            
            $this->model = $this->dependencies->get("Model");
            $this->controller = $this->dependencies->get("Controller");

        }
        
        public function renderContent() {
            
            $this->mainTemplate();
            
        }
        
        public function renderMeta() {
            
            $this->metaTemplate();
            
        }

        public function renderStyle() {
            
            $this->styleTemplate();
            
        }
        
    }

?>