package ajou.database.nearby;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

/**
 * JMC basic function class
 * 
 * @author Jeong, Munchang
 * @since Create: 2012. 06. 08 / Update: 2012. 08. 28
 */
public class JMCFunction {
	/**
	 * checkDebug - Check debug mode
	 * 
	 * @author Jeong, Munchang
	 * @param context
	 * @return
	 */
	public static boolean checkDebug(Context context) {
		if (context.getString(R.string.debug).equals("true")) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * setDebugLogI - Print information log at android LogCat when the string R.string.debug is "true"
	 * 
	 * @author Jeong, Munchang
	 * @param context
	 * @param string
	 */
	public static void setDebugLogI (Context context, String string) {
		//Check debug mode
		if (context.getString(R.string.debug).equals("true")) {
			Log.i(context.getString(R.string.log_tag), string);
		}
	}
	
	/**
	 * setDebugLogE - Print error log at android LogCat when the string R.string.debug is "true"
	 * 
	 * @author Jeong, Munchang
	 * @param context
	 * @param string
	 */
	public static void setDebugLogE (Context context, String string) {
		//Check debug mode
		if (context.getString(R.string.debug).equals("true")) {
			Log.e(context.getString(R.string.log_tag), string);
		}
	}
	
	/**
	 * getJsonData - Download Json data from server
	 * 
	 * @author Jeong, Munchang
	 * @param context
	 * @param address
	 * @return html.toString()
	 */
	public static String getJsonData(Context context, String address, String parameter) {
		// TODO 서버 접속 불가 시 강제 종료 되는 에러 처리 필요
		StringBuilder html = new StringBuilder();
		try {
			// URL 설정하고 접속하기
			URL url = new URL(address); // URL설정
			HttpURLConnection http = (HttpURLConnection) url.openConnection(); // 접속

			// 전송 모드 설정
			http.setDefaultUseCaches(false);
			http.setDoInput(true); // 서버에서 읽기 모드 지정
			http.setDoOutput(true); // 서버로 쓰기 모드 지정
			http.setRequestMethod("POST"); // 전송 방식은 POST

			// 서버로 값 전송
			StringBuffer buffer = new StringBuffer();
			buffer.append(parameter);
			OutputStreamWriter outStream = new OutputStreamWriter(
					http.getOutputStream(), "UTF-8");
			PrintWriter writer = new PrintWriter(outStream);
			writer.write(buffer.toString());
			writer.flush();

			// 서버에서 전송받기
			InputStreamReader tmp = new InputStreamReader(
					http.getInputStream(), "UTF-8");
			BufferedReader reader = new BufferedReader(tmp);
			String str;
			while ((str = reader.readLine()) != null) { // 서버에서 라인단위로 보내줄 것이므로
														// 라인단위로 읽는다

				html.append(str + "\n"); // View에 표시하기 위해 라인 구분자 추가 }
			}
		} catch (MalformedURLException e) {
			JMCFunction.setDebugLogE(context, "MalformedURLException");
		} catch (IOException e) {
			JMCFunction.setDebugLogE(context, "IOException");
		}
		return html.toString(); // 전송결과를 전역 변수에 저장 
	}
	
	public static void registerPush(Context context, String sender) {
		Intent registrationIntent = new Intent("com.google.android.c2dm.intent.REGISTER");
	    registrationIntent.putExtra("app", PendingIntent.getBroadcast(context, 0, new Intent(), 0));
	    registrationIntent.putExtra("sender", sender);
	    context.startService(registrationIntent);
	}

	public static void unregisterPush(Context context) {
		Intent unregistrationIntent = new Intent("com.google.android.c2dm.intent.UNREGISTER");
	    unregistrationIntent.setPackage(context.getPackageName());
	    unregistrationIntent.putExtra("app", PendingIntent.getBroadcast(context, 0, new Intent(), 0));
	    context.startService(unregistrationIntent);
	}
}
