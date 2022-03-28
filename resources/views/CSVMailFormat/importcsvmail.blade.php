<html>
<div>
    <p>Hello, </p>
    <p>some changes need to your csv file to import metafield</p>
    @foreach(Session::get('mailData') as $rk=>$rv)
        <p>{{$rv}}</p>
    @endforeach

    <p>Thanks,<br>
        The Metafields Configuration Team</p>
</div>
</html>
