@if (isset($statusCode) && $statusCode === 200)
<div class="table-responsive">
    
<table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>
                  Clusters Url
                </th>
                <th >
                  Workers
                </th>
                <th >
                  Cores
                </th>
                <th >
                  Memory
                </th>
                <th>
                  State
                </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if (isset($masterinfo) && $masterinfo->url != '')
              <tr>
                <td>
                  Cluster 1 
                </td>
                <td>
                  {{ $masterinfo->aliveworkers }}
                </td>
                <td>
                  {{ $masterinfo->cores }}
                </td>
                <td>
                  {{ $masterinfo->memory }}
                </td>
                <td>
                   {{ $masterinfo->status }}
                </td>
                <td>                  
                  <a href="{{ url('pypark') }}" class="btn btn-info"> Edit </a>
                </td>
              </tr>
              @if ($masterinfo->aliveworkers > 0)
                <tr>
                   <td colspan="">
                     <b>Workers</b>
                   </td>
                   <td><b>Cores</b> </td>
                   <td><b>State</b> </td>
                   <td colspan="3"></td>
                </tr>

              @foreach ( $masterinfo->workers as $wk )
                <tr>
                  <td colspan="">
                    {{ $wk['host'] }}
                  </td>
                  <td>
                    {{ $wk['cores'] }}
                  </td>
                  <td>
                    {{$wk['state']}}
                  </td>
                  <td colspan="3"></td>
                </tr>

              @endforeach
             @endif
              @else
              <tr>
                <td colspan="6">
                  <b>Cluster not existing.</b>
                </td>
              </tr>
              @endif
              
            </tbody>
          </table>
</div>
@else
-100
@endif