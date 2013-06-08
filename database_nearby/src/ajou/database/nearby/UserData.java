package ajou.database.nearby;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;

/**
 * UserData
 * 
 * @author Jeong, Munchang
 * @since Create: 2013. 06. 08 / Update: 2013. 06. 08
 */
public class UserData {

	public static void setMemberId(Context context, String string) {
		SharedPreferences pref = context.getSharedPreferences("UserDate", Activity.MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putString("member_id", string);
		editor.commit();
	}

	public static String getMemberId(Context context) {
		SharedPreferences pref = context.getSharedPreferences("commonData", Activity.MODE_PRIVATE);
		final String string = pref.getString("member_id", "");
		return string;
	}
	
	public static void setMemberUsername(Context context, String string) {
		SharedPreferences pref = context.getSharedPreferences("UserDate", Activity.MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putString("member_username", string);
		editor.commit();
	}

	public static String getMemberUsername(Context context) {
		SharedPreferences pref = context.getSharedPreferences("commonData", Activity.MODE_PRIVATE);
		final String string = pref.getString("member_username", "");
		return string;
	}

	public static void setMemberType(Context context, String string) {
		SharedPreferences pref = context.getSharedPreferences("UserDate", Activity.MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putString("member_type", string);
		editor.commit();
	}

	public static String getMemberType(Context context) {
		SharedPreferences pref = context.getSharedPreferences("commonData", Activity.MODE_PRIVATE);
		final String string = pref.getString("member_type", "");
		return string;
	}

	
	public static void setSessionId(Context context, String string) {
		SharedPreferences pref = context.getSharedPreferences("UserDate", Activity.MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putString("session_id", string);
		editor.commit();
	}

	public static String getSessionId(Context context) {
		SharedPreferences pref = context.getSharedPreferences("commonData", Activity.MODE_PRIVATE);
		final String string = pref.getString("session_id", "");
		return string;
	}
}
