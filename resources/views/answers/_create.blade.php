<div class="row mt-3">
 <div class="col-md-12">            
     <div class="card"> 
         <div class="card-body">
             <div class="card-title">
                 <h3>You Answer</h3>
             </div>
             

             <form action="{{route('questions.answers.store', $question->id)}}" method="post">
              @csrf
              <div class="form-group">
               
               <textarea id="body" class="form-control {{$errors->has('body') ? 'is-invalid' : '' }}" name="body" rows="7"></textarea>
               @if($errors->has('body'))
                <div class="invalid-feedback"><strong>{{$errors->first('body')}}</strong></div>
               @endif
              </div>
              <button type="submit" class="btn btn-lg btn-outline-primary">Submit</button>
             </form>
         </div>
     </div>
 </div>
</div>