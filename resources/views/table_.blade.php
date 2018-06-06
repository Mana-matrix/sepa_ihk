<label class="row col-sm-4 col-label"><?=$text?></label>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
    <tr>
        <th>id</th>
        <th>first name</th>
        <th>last name</th>
        <th>gender</th>
        <th>bic</th>
        <th>iban</th>
        <th>fee</th>
        <th>bank owner</th>
        <th>disable</th>

    </tr>
    </thead>
    @foreach($chars as $key => $value)
        <tr>
            <th class="thead-dark"><?= $value->id ?></th>
            <td><?= $value->firstname ?></td>
            <td><?= $value->lastname ?></td>
            <td><?= $value->gender?></td>
            <td><?= $value->xt_bic?></td>
            <td><?= $value->xt_iban?></td>
            <td><?= $value->xt_memberfee?></td>
            <td><?= $value->xt_bank_owner?></td>
            <td><?= $value->disable?></td>

        </tr>
    @endforeach
</table>
