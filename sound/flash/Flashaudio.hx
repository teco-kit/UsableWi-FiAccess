import flash.external.ExternalInterface;
import flash.utils.ByteArray;
import flash.utils.Timer;
import flash.media.Microphone;
import flash.media.SoundCodec;
import flash.events.SampleDataEvent;
import flash.system.Security;
import flash.system.SecurityPanel;


class Flashaudio
{
	private static var mic : Microphone;
	private static var mrate:Float;
	private static var data : ByteArray;
	private static var count:Int;
	private static var seconds:Float;
	private static var recording:Bool;
	
	private static var startTime:Float;
	private static var endTime:Float;

	public static function exportInit():Void
	{
		if(Microphone.isSupported)
		{
			mic = Microphone.getMicrophone();
			if(mic == null)
			{
				trace("kein Mikrofonzugriff!");
			}
			mic.gain = 100;

			mic.rate = 44;
			switch(mic.rate)
			{
			case 44:
				mrate = 44.1;
			case 22:
				mrate = 22.05;
			case 11:
				mrate = 11.025;
			case 8:
				mrate = 8.0;
			case 5:
				mrate = 5.512;
			}

			mic.setSilenceLevel(100);
			mic.setLoopBack(false);
			mic.setUseEchoSuppression(false);
			mic.codec = SoundCodec.NELLYMOSER;


			recording = false;
			startTime = 0;
		}
		else
		{
			trace("keine Mikrofonunterstuetzung!");
		}

		return;
	}

	public static function exportSettings():Void
	{
		Security.showSettings(PRIVACY);
		return;
	}

	public static function exportRecAudio(sec:Float):Void
	{
		if(recording == false)
		{
			recording = true;

			data = new ByteArray();
			count = 0;
			seconds = sec;
	
			mic.setSilenceLevel(0, Std.int(sec*1000));

			mic.addEventListener(SampleDataEvent.SAMPLE_DATA, micSampleDataHandler);
		}

		return;
	}
	
	public static function main()
	{
		ExternalInterface.addCallback("ccInit", exportInit);
		ExternalInterface.addCallback("ccRecAudio", exportRecAudio);
		ExternalInterface.addCallback("ccSettings", exportSettings);
	}

	private static function micSampleDataHandler(event:SampleDataEvent):Void
	{
		if(startTime == 0)
		{
			var date:Date = Date.now();
			startTime = date.getTime();
		}
			
		while(event.data.bytesAvailable > 0)
		{
			if(count == Std.int(seconds*mrate*1000))
			{
				var date:Date = Date.now();
				endTime = date.getTime();
				
				mic.removeEventListener(SampleDataEvent.SAMPLE_DATA, micSampleDataHandler);
				data.position = 0;
				sendData();
			}

        	var sample:Float = event.data.readFloat();
        	data.writeFloat(sample);
		count += 1;
		}

		return;
	}

	private static function sendData():Void
	{
		var string:String = "[[";
		var sample:Float = data.readFloat();
		string += sample;

		while(data.bytesAvailable > 0)
		{
			sample = data.readFloat();
			string += "," + sample;
		}
		
		string += "],[" + startTime + "," + endTime + "," + mrate + "]]";

		ExternalInterface.call("ccGetAudio", string);
		
		recording = false;

		return;
	}
}
