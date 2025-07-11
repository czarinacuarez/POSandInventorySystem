
                            <!-- Tab panes -->
                            <div class="tab-content">
                              <!-- 1ST TAB -->
                                <div class="tab-pane fade in mt-2" id="bags">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 1 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name?>" />
                                              <input type="hidden" name="price" value="<?php echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>

                          
                                  </div>
                                </div>
                              <!-- 2ND TAB -->
                              <div class="tab-pane fade in mt-2" id="daily">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 2 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name?>" />
                                              <input type="hidden" name="price" value="<?php echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>
                                  </div>
                                </div>
                              <!-- 3rd TAB -->
                              <div class="tab-pane fade in mt-2" id="drinks">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 3 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name?>" />
                                              <input type="hidden" name="price" value="<?php echo$cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>
                                  </div>
                                </div>
                              <!-- 4th TAB -->
                              <div class="tab-pane fade in mt-2" id="home">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 4 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name ?>" />
                                              <input type="hidden" name="price" value="<?php echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>

                          
                                  </div>
                                </div>
                                 <!-- 4th TAB -->
                              <div class="tab-pane fade in mt-2" id="shirts">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 5 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name?>" />
                                              <input type="hidden" name="price" value="<?php  echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>

                          
                                  </div>
                                </div>

                                 <!-- 4th TAB -->
                              <div class="tab-pane fade in mt-2" id="toys">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 6 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id ?>" />
                                              <input type="hidden" name="name" value="<?php echo  $cust->product_name?>" />
                                              <input type="hidden" name="price" value="<?php echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>

                          
                                  </div>
                                </div>
                                 <!-- 4th TAB -->
                              <div class="tab-pane fade in mt-2" id="valentines">
                                  <div class="row">
                                  <?php
                                      $ret = "SELECT * FROM  product WHERE category_id = 7 ";
                                      $stmt = $mysqli->prepare($ret);
                                      $stmt->execute();
                                      $res = $stmt->get_result();
                                      while ($cust = $res->fetch_object()) {
                                      ?>
                                    <div class="col-sm-4 col-md-2" >
                                      
                                      <form method="post">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $cust->product_name ?></h6>
                                              <h6>₱ <?php echo $cust->product_price; ?></h6>
                                              <input type="number" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $cust->product_name?>" />
                                              <input type="hidden" name="prodid" value="<?php echo $cust->product_id?>" />
                                              <input type="hidden" name="price" value="<?php echo $cust->product_price ?>" />
                                              <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info"
                                                     value="Add" />
                                          </div>
                                                      
                        
                                      </form>
                                    </div>
                                    <?php
                                        }
                                        ?>

                          
                                  </div>
                                </div>

<!-- wala na di nadala sa tab pane, dalom nana di na part -->
                            </div>
                        </div>
                        <!-- /.panel-body -->
                      </div>
                    </div>
                  </div>