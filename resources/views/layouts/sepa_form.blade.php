


            <form action="{{ url('/sepa') }}" method="POST" >

                <div class="form-group row ">
                    <label for="first_sepa" class="col-sm-5 my-1 col-form-label text-md-right">First Sepa</label>
                    <div class="col-md-7">
                        <input type="date" name="first_sepa" required="required" autofocus="autofocus" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="follow_sepa" class="col-sm-5 col-form-label text-md-right">Follow Sepa</label>
                    <div class="col-md-7">
                        <input type="date" name="follow_sepa" required="required" autofocus="autofocus" class="form-control">
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 my-1 offset-md-3">
                        <button type="submit" class="btn btn-primary">generate new Sepa-xml</button>
                    </div>
                </div>
                <input type="hidden" name="generate" value="true">
                @csrf

            </form>








