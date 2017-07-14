  @extends('layouts.affiliate')


  @section('leadstable')
  @parent

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>Landing Page</th>
              <td>{{ $landingPageURL }}</td>
              <td><button onclick="copyToClipboard('{{ $landingPageURL }}')">Copy</button></td>
            </tr>
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>Plan</th>
              <th>Active</th>
              <th>Deleted</th>
              <th>Link</th>

            </tr>
            @foreach($links as $link)
            @if (!$link->active || $link->deleted)
            <tr style="background-color: red;">
            @else
            <tr>
            @endif
              <td>{{ $link->plan_name }}</td>
              <td>{{ $link->active ? 'Yes' : 'No' }}</td>
              <td>{{ $link->deleted ? 'Yes' : 'No' }}</td>
              <td>{{ $baseUrl }}{{ $link->code }}</td>
              <td><button onclick="copyToClipboard('{{ $baseUrl }}{{ $link->code }}')">Copy</button></td>
            </tr>
            @endforeach
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  <script type="text/javascript">
    function copyToClipboard(text) {

      // Create an auxiliary hidden input
      var aux = document.createElement("input");

      // Get the text from the element passed into the input
      aux.setAttribute("value", text);

      // Append the aux input to the body
      document.body.appendChild(aux);

      // Highlight the content
      aux.select();

      // Execute the copy command
      document.execCommand("copy");

      // Remove the input from the body
      document.body.removeChild(aux);

    }

  </script>

  @endsection