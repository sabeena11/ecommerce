@extends('layouts.app')

@section('content')

  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Upload Image</h5>
        <div class="card">
          <div class="card-body">
            <form>
              

              <div class="mb-3">
                <div class="mb-3">
                  <!-- <input type="hidden" id="image_id" name="image_id" value=""> -->
                  <label for="image" class="form-label">Image</label>
                  <div id="image" class="dropzone dz-clickable">
                      <div class="dz-message needsclick">
                          <br>Drop files here or click to upload.<br><br>
                      </div>
                  </div>
                </div>
              </div>
              
              
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('Customjs')


<script>   
  Dropzone.autoDiscover = false;    
  const dropzone = $("#image").dropzone({ 
      init: function() {
          this.on('addedfile', function(file) {
              if (this.files.length > 1) {
                  this.removeFile(this.files[0]);
              }
          });
      },
      
      maxFiles: 1,
      paramName: 'image',
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/gif",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }, success: function(file, response){
          $("#image_id").val(response.image_id);
          //console.log(response)
      }
  });
</script>


@endsection