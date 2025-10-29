<?php
namespace App\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\Generalmail;
use PHPMailer\PHPMailer\PHPMailer;
class EmailService {


    public function sendEmail($details,$destinations)
    {
        // $details = [
        //     'title' => 'Mail from My Application',
        //     'body' => 'This is for testing email using SMTP'
        // ];
         try{
            Mail::to($destinations)->send(new \App\Mail\GeneralMail($details));
            // return 1;
         }catch (\Exception $e){
            return back();
         }



        return 'Email sent!';
    }
    public function build($details,$destinations)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port       = env('MAIL_PORT');

            // Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($destinations);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Refuser Rejoindre Equipe';
            $mail->Body    = "Madame/Monsieur {$details['title']},<br><br>J'ai le plaisir de vous informer que votre demande a été examinée avec attention et a été Refusée. Nous sommes ravis de pouvoir répondre à vos attentes et restons à votre disposition pour toute question complémentaire.<br><br>Cordialement,<br><br><a href=\"http://localhost/limos/login.php\">login et faire une autre demande !!</a>";

            $mail->send();

            return 1;
            // return $this->subject($mail->Subject)
            //             ->view('emails.blank'); // You can create a blank view if needed

        } catch (\Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
