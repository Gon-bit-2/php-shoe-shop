# API Endpoint: GET /chat/modes

## 📋 Mô Tả

API endpoint để lấy danh sách các chế độ (modes) chatbot khả dụng. Frontend có thể gọi API này để hiển thị dropdown/select box cho người dùng chọn.

---

## 🔗 Endpoint

```
GET /v1/api/chat/modes
```

---

## 🔑 Authentication

**Required:** Yes (Bearer Token)

Header:

```
Authorization: Bearer <your_token>
```

---

## 📤 Request

Không cần parameters hoặc body.

### Example Request

```bash
curl -X GET http://localhost:3456/v1/api/chat/modes \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📥 Response

### Success Response (200 OK)

```json
{
  "success": true,
  "modes": [
    {
      "id": "admission",
      "name": "Tư vấn tuyển sinh",
      "description": "Tư vấn tuyển sinh",
      "icon": "🎓",
      "isAvailable": true,
      "requiresIndex": true
    },
    {
      "id": "student-support",
      "name": "Hỗ trợ sinh viên",
      "description": "Hỗ trợ sinh viên",
      "icon": "🎒",
      "isAvailable": false,
      "requiresIndex": true,
      "error": "FAISS index chưa được tạo. Chạy: node ingest.js --mode student-support"
    },
    {
      "id": "web-search",
      "name": "Tìm kiếm web",
      "description": "Tìm kiếm web",
      "icon": "🌐",
      "isAvailable": true,
      "requiresIndex": false
    },
    {
      "id": "vhu",
      "name": "Chế độ VHU (Legacy)",
      "description": "Chế độ VHU (legacy)",
      "icon": "🏫",
      "isAvailable": true,
      "requiresIndex": true
    }
  ],
  "total": 4
}
```

### Response Fields

| Field     | Type    | Description        |
| --------- | ------- | ------------------ |
| `success` | boolean | Trạng thái request |
| `modes`   | array   | Danh sách các mode |
| `total`   | number  | Tổng số mode       |

### Mode Object Fields

| Field           | Type    | Description                                      |
| --------------- | ------- | ------------------------------------------------ |
| `id`            | string  | ID của mode (dùng khi gọi API chat)              |
| `name`          | string  | Tên hiển thị                                     |
| `description`   | string  | Mô tả mode                                       |
| `icon`          | string  | Emoji icon                                       |
| `isAvailable`   | boolean | Mode có sẵn sàng sử dụng không                   |
| `requiresIndex` | boolean | Mode có cần FAISS index không                    |
| `error`         | string  | (Optional) Thông báo lỗi nếu mode không khả dụng |

---

## ❌ Error Response

### 500 Internal Server Error

```json
{
  "success": false,
  "error": "Lỗi khi lấy danh sách modes"
}
```

---

## 💻 Frontend Integration Examples

### React Example

```jsx
import { useState, useEffect } from "react";

function ChatModeSelector() {
  const [modes, setModes] = useState([]);
  const [selectedMode, setSelectedMode] = useState("admission");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchModes();
  }, []);

  const fetchModes = async () => {
    try {
      const response = await fetch("/v1/api/chat/modes", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await response.json();

      if (data.success) {
        // Chỉ hiển thị các mode khả dụng
        const availableModes = data.modes.filter((m) => m.isAvailable);
        setModes(availableModes);

        // Set mode mặc định là mode đầu tiên khả dụng
        if (availableModes.length > 0) {
          setSelectedMode(availableModes[0].id);
        }
      }
    } catch (error) {
      console.error("Error fetching modes:", error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading modes...</div>;

  return (
    <div>
      <label>Chọn chế độ chat:</label>
      <select
        value={selectedMode}
        onChange={(e) => setSelectedMode(e.target.value)}
      >
        {modes.map((mode) => (
          <option key={mode.id} value={mode.id}>
            {mode.icon} {mode.name}
          </option>
        ))}
      </select>
    </div>
  );
}
```

### Vue.js Example

```vue
<template>
  <div>
    <select v-model="selectedMode">
      <option v-for="mode in availableModes" :key="mode.id" :value="mode.id">
        {{ mode.icon }} {{ mode.name }}
      </option>
    </select>
  </div>
</template>

<script>
export default {
  data() {
    return {
      modes: [],
      selectedMode: "admission",
    };
  },
  computed: {
    availableModes() {
      return this.modes.filter((m) => m.isAvailable);
    },
  },
  mounted() {
    this.fetchModes();
  },
  methods: {
    async fetchModes() {
      try {
        const response = await fetch("/v1/api/chat/modes", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const data = await response.json();

        if (data.success) {
          this.modes = data.modes;
        }
      } catch (error) {
        console.error("Error:", error);
      }
    },
  },
};
</script>
```

### Vanilla JavaScript Example

```javascript
async function loadChatModes() {
  try {
    const response = await fetch("/v1/api/chat/modes", {
      headers: {
        Authorization: `Bearer ${getToken()}`,
      },
    });

    const data = await response.json();

    if (data.success) {
      const selectElement = document.getElementById("chatMode");

      data.modes
        .filter((mode) => mode.isAvailable)
        .forEach((mode) => {
          const option = document.createElement("option");
          option.value = mode.id;
          option.textContent = `${mode.icon} ${mode.name}`;
          selectElement.appendChild(option);
        });
    }
  } catch (error) {
    console.error("Error loading modes:", error);
  }
}

// Call on page load
document.addEventListener("DOMContentLoaded", loadChatModes);
```

---

## 🎨 UI Examples

### Simple Dropdown

```html
<select id="chatMode">
  <option value="admission">🎓 Tư vấn tuyển sinh</option>
  <option value="student-support">🎒 Hỗ trợ sinh viên</option>
  <option value="web-search">🌐 Tìm kiếm web</option>
</select>
```

### Radio Buttons

```html
<div class="mode-selector">
  <label>
    <input type="radio" name="mode" value="admission" checked />
    🎓 Tư vấn tuyển sinh
  </label>
  <label>
    <input type="radio" name="mode" value="student-support" />
    🎒 Hỗ trợ sinh viên
  </label>
  <label>
    <input type="radio" name="mode" value="web-search" />
    🌐 Tìm kiếm web
  </label>
</div>
```

### Cards/Buttons

```html
<div class="mode-cards">
  <div class="mode-card" data-mode="admission">
    <span class="icon">🎓</span>
    <h3>Tư vấn tuyển sinh</h3>
    <p>Thông tin xét tuyển, học phí, ngành học</p>
  </div>
  <div class="mode-card" data-mode="student-support">
    <span class="icon">🎒</span>
    <h3>Hỗ trợ sinh viên</h3>
    <p>Lịch học, thủ tục, cơ sở vật chất</p>
  </div>
  <div class="mode-card" data-mode="web-search">
    <span class="icon">🌐</span>
    <h3>Tìm kiếm web</h3>
    <p>Tìm kiếm thông tin từ internet</p>
  </div>
</div>
```

---

## 🔄 Workflow

1. **Frontend load:** Gọi `GET /chat/modes` khi trang load
2. **Display modes:** Hiển thị dropdown/buttons với các mode khả dụng
3. **User selects mode:** Người dùng chọn mode
4. **Send message:** Gửi message kèm `mode` trong body:
   ```json
   {
     "message": "...",
     "sessionId": "...",
     "mode": "admission"
   }
   ```

---

## 💡 Best Practices

### 1. Cache modes

```javascript
// Cache modes trong sessionStorage
const getCachedModes = () => {
  const cached = sessionStorage.getItem("chatModes");
  if (cached) {
    const data = JSON.parse(cached);
    // Cache 1 giờ
    if (Date.now() - data.timestamp < 3600000) {
      return data.modes;
    }
  }
  return null;
};

const setCachedModes = (modes) => {
  sessionStorage.setItem(
    "chatModes",
    JSON.stringify({
      modes,
      timestamp: Date.now(),
    })
  );
};
```

### 2. Handle unavailable modes

```javascript
// Disable modes that are not available
modes.forEach((mode) => {
  if (!mode.isAvailable) {
    console.warn(`Mode ${mode.id} is not available: ${mode.error}`);
    // Show tooltip or disable option
  }
});
```

### 3. Set smart defaults

```javascript
// Prioritize admission mode for new users
const defaultMode =
  modes.find((m) => m.id === "admission" && m.isAvailable) ||
  modes.find((m) => m.isAvailable) ||
  "vhu";
```

---

## 🧪 Testing

```bash
# Test with curl
curl -X GET http://localhost:3456/v1/api/chat/modes \
  -H "Authorization: Bearer YOUR_TOKEN" \
  | jq '.'

# Expected output: JSON with modes array
```

---

## 📝 Notes

- API này không yêu cầu parameters
- Response sẽ bao gồm cả modes chưa sẵn sàng (có `isAvailable: false`)
- Frontend nên filter chỉ hiển thị modes có `isAvailable: true`
- Mode `web-search` không cần FAISS index nên luôn available
- Nếu FAISS index chưa tạo, mode sẽ có `isAvailable: false` và field `error` chứa hướng dẫn

---

## 🔗 Related APIs

- `POST /chat` - Gửi message với mode đã chọn
- `POST /chat/conversation` - Tạo conversation mới
- `GET /chat/history/:sessionId` - Lấy lịch sử chat
