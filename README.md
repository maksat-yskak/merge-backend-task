# mergy-backend-test

Project uses Slim Framework and respect/validation

## API Reference

#### Get user list

```http
  GET /api/users
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `api_key` | `string` | **Required**. Your API key |

#### Get user

```http
  GET /api/user/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of user          |

#### Create user

```http
  POST /api/user/add
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of user          |
| `name` | `string` | only characters no symbols or numbers |
| `email` | `string` | can contain numbers and characters |
| `job` | `string` | only characters no symbols or numbers |
| `cv` | `string` | url, ends with .doc, .docx, .pdf |
| `user_image` | `string` | url, ends with .png, .jpg, .jpeg |
| `experience` | `array` | array of json objects |
| `experience['job_title']` | `string` | only characters no symbols or numbers |
| `experience['location']` | `string` | country code ex. KZ |
| `experience['start_date']` | `string` | dd/mm/yyyy |
| `experience['end_date']` | `string` | dd/mm/yyyy |

#### Update user

```http
  PUT /api/user/update/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of user          |
| `name` | `string` | only characters no symbols or numbers |
| `email` | `string` | can contain numbers and characters |
| `job` | `string` | only characters no symbols or numbers |
| `cv` | `string` | url, ends with .doc, .docx, .pdf |
| `user_image` | `string` | url, ends with .png, .jpg, .jpeg |
| `experience` | `array` | array of json objects |
| `experience['job_title']` | `string` | only characters no symbols or numbers |
| `experience['location']` | `string` | country code ex. KZ |
| `experience['start_date']` | `string` | dd/mm/yyyy |
| `experience['end_date']` | `string` | dd/mm/yyyy |

#### Delete user

```http
  DELETE /api/user/delete/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of user          |

### Example

```http
{
  "id": "ahd123@d",
  "name": "username",
  "email": "username@gmail.com",
  "job": "user job",
  "cv": "http://www.google.com/asda.docx",
  "user_image": "http://www.google.com/asd.jpeg",
  "experience": [
    {
      "job_title": "Dev",
      "location": "KZ",
      "start_date": "11/11/2011",
      "end_date": "11/11/2011"
    },
    {
      "job_title": "DevOps",
      "location": "KZ",
      "start_date": "12/12/2012",
      "end_date": "12/12/2012"
    }
  ]
}
```