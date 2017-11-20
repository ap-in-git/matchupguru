
<div class="container">

  <!-- Trigger the modal with a button -->
  <!-- Modal -->
  <div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <br>
          <div class="bs-example bs-example-tabs">
              <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
                <li class=""><a href="#signup" data-toggle="tab">Register</a></li>

              </ul>
          </div>
        <div class="modal-body">
          <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade active in" id="signin">
           <Login></Login>
          </div>
          <div class="tab-pane fade" id="signup">
                <Register></Register>
        </div>
      </div>
        </div>
        <div class="modal-footer">
        <center>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
