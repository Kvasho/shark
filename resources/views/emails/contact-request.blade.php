<!DOCTYPE html>
<html lang="ka">
<head><meta charset="UTF-8"><title>ახალი მოთხოვნა</title></head>
<body style="margin:0;padding:30px;background:#f5f5f5;font-family:Arial,sans-serif;color:#17262d">
    <div style="max-width:640px;margin:auto;padding:35px;background:#fff;border-top:5px solid #f97316">
        <h1 style="margin:0 0 25px;font-size:26px">ახალი მოთხოვნა SHARK-ის ვებსაიტიდან</h1>
        <p><strong>სახელი:</strong> {{ $data['name'] }}</p>
        <p><strong>ელფოსტა:</strong> <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></p>
        <p><strong>ტელეფონი:</strong> {{ $data['phone'] }}</p>
        <p><strong>სერვისი:</strong> {{ $data['service'] ?: 'არ არის მითითებული' }}</p>
        <hr style="margin:25px 0;border:0;border-top:1px solid #eee">
        <p style="white-space:pre-line;line-height:1.7">{{ $data['message'] }}</p>
    </div>
</body>
</html>
