/* The List powered by Creative Commons

   Copyright (C) 2014, 2015 Creative Commons

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

package org.creativecommons.thelist.utils;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.support.v4.app.NotificationCompat;

import com.afollestad.materialdialogs.MaterialDialog;

import org.creativecommons.thelist.R;
import org.creativecommons.thelist.activities.MainActivity;
import org.creativecommons.thelist.activities.StartActivity;

public class MessageHelper {
    public static final String TAG = RequestMethods.class.getSimpleName();
    protected Context mContext;

    //Notification Ids
    protected static final int mNotifySuccess = 42341234;
    protected static final int mNotifyFail = 4234133;

    //Set Context
    public MessageHelper(Context mc) {
        mContext = mc;
    }

    //Material Design Dialog
    public void showDialog(Context context, String title, String message){
        new MaterialDialog.Builder(mContext)
                .title(title)
                .content(message)
                .positiveText(R.string.ok_label)
                        //.negativeText(R.string.disagree)
                .show();
    }

    //Send Android System Notifications
    public void sendNotification(Context context, String title, String message, String ticker, int id){
        //Bitmap largeIcon = BitmapFactory.decodeResource(mContext.getResources(), R.drawable.ic_launcher);

        NotificationCompat.Builder mBuilder = new NotificationCompat.Builder (context)
                .setSmallIcon(R.drawable.ic_camera_alt_white_24dp)
                .setContentTitle(title)
                .setContentText(message)
                .setTicker(ticker);

        Intent resultIntent = new Intent(mContext, MainActivity.class);
        android.support.v4.app.TaskStackBuilder stackBuilder = android.support.v4.app.TaskStackBuilder.create(mContext);
        stackBuilder.addParentStack(StartActivity.class);
        stackBuilder.addNextIntent(resultIntent);
        PendingIntent resultPendingIntent =
                stackBuilder.getPendingIntent(
                        0,
                        PendingIntent.FLAG_UPDATE_CURRENT
                );
        mBuilder.setContentIntent(resultPendingIntent);

        NotificationManager manager = (NotificationManager) mContext.getSystemService(Context.NOTIFICATION_SERVICE);
        manager.notify(id, mBuilder.build());
    }

    // --------------------------------------------------------
    // GENERAL MESSAGES
    // --------------------------------------------------------

    public void NetworkFailMessage(){
        //showDialog(mContext, );
    }


    // --------------------------------------------------------
    // PHOTO REQUEST MESSAGES
    // --------------------------------------------------------

    //NOTIFICATIONS

    public void notifyUploadSuccess(){
        sendNotification(mContext, "The List",
                "Your photo has been uploaded successfully!",
                "List Photo Uploaded Successfully", mNotifySuccess);
    }

    public void notifyUploadFail(){
        sendNotification(mContext, "The List",
                "There was a problem uploading your photo.",
                "List Photo Uploaded Failed", mNotifyFail);
    }

    //DIALOGS

    public void photoNetworkFailMessage(){
        showDialog(mContext, mContext.getString(R.string.upload_failed_title_network),
                mContext.getString(R.string.upload_failed_text_network));
    }

    public void photoSizeFailMessage(){
        showDialog(mContext, mContext.getString(R.string.upload_failed_title_filesize),
                mContext.getString(R.string.upload_failed_text_filesize));
    }

} //MessageHelper
