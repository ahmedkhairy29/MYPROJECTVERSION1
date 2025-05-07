<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>تفعيل حسابك</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background-color: white; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">مرحباً {{ $user->name ?? 'مستخدم' }} 👋</h2>
        <p style="font-size: 16px; color: #555;">شكراً لتسجيلك في نظامنا. الرجاء النقر على الزر أدناه لتفعيل حسابك:</p>
        
        <a href="{{ $activation_url }}" style="display: inline-block; padding: 12px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;">
            تفعيل الحساب
        </a>

        <p style="font-size: 14px; color: #888; margin-top: 30px;">
            إذا لم تقم بإنشاء حساب لدينا، يمكنك تجاهل هذه الرسالة.
        </p>

        <p style="font-size: 14px; color: #aaa;">مع التحية،<br>فريق الدعم</p>
    </div>
</body>
</html>



