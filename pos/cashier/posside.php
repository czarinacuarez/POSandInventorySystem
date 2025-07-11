<!-- SIDE PART NA SUMMARY -->
        <div class="card-body col-md-3">
      
          
<form method = "post">
          <input type="hidden" name="date" value="<?php ?>">
          <div class="form-group row text-left mb-3">
            <div class="col">
                    <label>Customer</label>
                    <?php
                      $ret = "SELECT * FROM buyer ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <select name="categorys" class="form-control" required>
                      
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['buyer_id'] ?>><?php echo $ri['first_name'] ?> <?php echo $ri['last_name'] ?></option>
                    <?php }?>
                    </select>
                  </div>
          </div>
          <div class="form-group row text-left mb-2">

            <div class="col-sm-5 text-primary">
              <h6 class="font-weight-bold py-2">
                Total
              </h6>
            </div>

            <div class="col-sm-7">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">₱</span>
                </div>
                <?php $total = $mysqli->query("SELECT SUM(price) AS sums FROM cart ")->fetch_object()->sums; ?> 
                <input type="hidden" name="trancode" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                <input type="text" class="form-control text-right " value="<?php echo $total ?>" readonly name="total">
              </div>
            </div>
            <div class="col-sm-5 text-primary">
              <h6 class="font-weight-bold py-2">
                Cash
              </h6>
            </div>
            <div class="col-sm-7">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">₱</span>
                </div>
                <input type="number" class="form-control text-right " name = "cash" value="">
              </div>
            </div>

          </div>
<?php ?>       
          <button type="submit" name = "submit" class="btn btn-block btn-success" >SUBMIT</button>
          <button type="submit" name = "reset" class="btn btn-block btn-danger" >RESET ALL</button>

        
        <!-- END OF Modal -->

</form>
      </div> <!-- END OF CARD BODY -->

     </div>

