import json
import sys


def format_timestamp(seconds):
    ms = int(round((seconds - int(seconds)) * 1000))

    if ms == 1000:
        seconds += 1
        ms = 0

    h = int(seconds // 3600)
    m = int((seconds % 3600) // 60)
    s = int(seconds % 60)

    return f"{h:02}:{m:02}:{s:02},{ms:03}"


def build_chunks(words, max_chars=42, max_lines=2):
    chunks = []

    current_words = []
    lines = [""]
    
    for word_data in words:
        word = word_data["word"].strip()

        current_line = lines[-1]

        if not current_line:
            candidate = word
        else:
            candidate = current_line + " " + word

        if len(candidate) <= max_chars:
            lines[-1] = candidate
            current_words.append(word_data)
            continue

        # start new line
        if len(lines) < max_lines:
            lines.append(word)
            current_words.append(word_data)
            continue

        # subtitle full -> flush it
        chunks.append({
            "start": current_words[0]["start"],
            "end": current_words[-1]["end"],
            "text": "\n".join(lines)
        })

        current_words = [word_data]
        lines = [word]

    if current_words:
        chunks.append({
            "start": current_words[0]["start"],
            "end": current_words[-1]["end"],
            "text": "\n".join(lines)
        })

    return chunks


def create_chunk(words, max_chars):
    lines = []
    current_line = ""

    for word_data in words:
        word = word_data["word"].strip()

        candidate = word if not current_line else f"{current_line} {word}"

        if len(candidate) <= max_chars:
            current_line = candidate
        else:
            lines.append(current_line)
            current_line = word

    if current_line:
        lines.append(current_line)

    return {
        "start": words[0]["start"],
        "end": words[-1]["end"],
        "text": "\n".join(lines),
    }


def json_to_srt(json_path, srt_path, max_chars=42, max_lines=2):
    with open(json_path, "r", encoding="utf-8") as f:
        data = json.load(f)

    subtitle_index = 1
    output = []

    for segment in data["segments"]:

        words = [
            w for w in segment.get("words", [])
            if "start" in w and "end" in w
        ]

        chunks = build_chunks(
            words,
            max_chars=max_chars,
            max_lines=max_lines
        )

        for chunk in chunks:

            output.append(str(subtitle_index))

            output.append(
                f"{format_timestamp(chunk['start'])} --> "
                f"{format_timestamp(chunk['end'])}"
            )

            output.append(chunk["text"])
            output.append("")

            subtitle_index += 1

    with open(srt_path, "w", encoding="utf-8") as f:
        f.write("\n".join(output))


if __name__ == "__main__":
    json_path = sys.argv[1]
    srt_path = sys.argv[2]

    max_chars = int(sys.argv[3]) if len(sys.argv) > 3 else 42
    max_lines = int(sys.argv[4]) if len(sys.argv) > 4 else 2

    json_to_srt(
        json_path,
        srt_path,
        max_chars=max_chars,
        max_lines=max_lines,
    )