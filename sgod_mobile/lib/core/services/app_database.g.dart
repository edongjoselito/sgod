// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'app_database.dart';

// ignore_for_file: type=lint
class $UserProfileTableTable extends UserProfileTable
    with TableInfo<$UserProfileTableTable, UserProfileRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $UserProfileTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultValue: const Constant(0),
  );
  static const VerificationMeta _usernameMeta = const VerificationMeta(
    'username',
  );
  @override
  late final GeneratedColumn<String> username = GeneratedColumn<String>(
    'username',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _positionMeta = const VerificationMeta(
    'position',
  );
  @override
  late final GeneratedColumn<String> position = GeneratedColumn<String>(
    'position',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _fnameMeta = const VerificationMeta('fname');
  @override
  late final GeneratedColumn<String> fname = GeneratedColumn<String>(
    'fname',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _lnameMeta = const VerificationMeta('lname');
  @override
  late final GeneratedColumn<String> lname = GeneratedColumn<String>(
    'lname',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _emailMeta = const VerificationMeta('email');
  @override
  late final GeneratedColumn<String> email = GeneratedColumn<String>(
    'email',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _avatarMeta = const VerificationMeta('avatar');
  @override
  late final GeneratedColumn<String> avatar = GeneratedColumn<String>(
    'avatar',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _sectionMeta = const VerificationMeta(
    'section',
  );
  @override
  late final GeneratedColumn<String> section = GeneratedColumn<String>(
    'section',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _secGroupMeta = const VerificationMeta(
    'secGroup',
  );
  @override
  late final GeneratedColumn<String> secGroup = GeneratedColumn<String>(
    'sec_group',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _loginSourceMeta = const VerificationMeta(
    'loginSource',
  );
  @override
  late final GeneratedColumn<String> loginSource = GeneratedColumn<String>(
    'login_source',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _updatedAtMeta = const VerificationMeta(
    'updatedAt',
  );
  @override
  late final GeneratedColumn<DateTime> updatedAt = GeneratedColumn<DateTime>(
    'updated_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    username,
    position,
    fname,
    lname,
    email,
    avatar,
    section,
    secGroup,
    loginSource,
    updatedAt,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'user_profile_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<UserProfileRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('username')) {
      context.handle(
        _usernameMeta,
        username.isAcceptableOrUnknown(data['username']!, _usernameMeta),
      );
    } else if (isInserting) {
      context.missing(_usernameMeta);
    }
    if (data.containsKey('position')) {
      context.handle(
        _positionMeta,
        position.isAcceptableOrUnknown(data['position']!, _positionMeta),
      );
    }
    if (data.containsKey('fname')) {
      context.handle(
        _fnameMeta,
        fname.isAcceptableOrUnknown(data['fname']!, _fnameMeta),
      );
    }
    if (data.containsKey('lname')) {
      context.handle(
        _lnameMeta,
        lname.isAcceptableOrUnknown(data['lname']!, _lnameMeta),
      );
    }
    if (data.containsKey('email')) {
      context.handle(
        _emailMeta,
        email.isAcceptableOrUnknown(data['email']!, _emailMeta),
      );
    }
    if (data.containsKey('avatar')) {
      context.handle(
        _avatarMeta,
        avatar.isAcceptableOrUnknown(data['avatar']!, _avatarMeta),
      );
    }
    if (data.containsKey('section')) {
      context.handle(
        _sectionMeta,
        section.isAcceptableOrUnknown(data['section']!, _sectionMeta),
      );
    }
    if (data.containsKey('sec_group')) {
      context.handle(
        _secGroupMeta,
        secGroup.isAcceptableOrUnknown(data['sec_group']!, _secGroupMeta),
      );
    }
    if (data.containsKey('login_source')) {
      context.handle(
        _loginSourceMeta,
        loginSource.isAcceptableOrUnknown(
          data['login_source']!,
          _loginSourceMeta,
        ),
      );
    }
    if (data.containsKey('updated_at')) {
      context.handle(
        _updatedAtMeta,
        updatedAt.isAcceptableOrUnknown(data['updated_at']!, _updatedAtMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  UserProfileRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return UserProfileRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      username: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}username'],
      )!,
      position: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}position'],
      )!,
      fname: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}fname'],
      )!,
      lname: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}lname'],
      )!,
      email: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}email'],
      ),
      avatar: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}avatar'],
      ),
      section: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}section'],
      )!,
      secGroup: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}sec_group'],
      )!,
      loginSource: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}login_source'],
      )!,
      updatedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}updated_at'],
      )!,
    );
  }

  @override
  $UserProfileTableTable createAlias(String alias) {
    return $UserProfileTableTable(attachedDatabase, alias);
  }
}

class UserProfileRow extends DataClass implements Insertable<UserProfileRow> {
  final int id;
  final String username;
  final String position;
  final String fname;
  final String lname;
  final String? email;
  final String? avatar;
  final String section;
  final String secGroup;
  final String loginSource;
  final DateTime updatedAt;
  const UserProfileRow({
    required this.id,
    required this.username,
    required this.position,
    required this.fname,
    required this.lname,
    this.email,
    this.avatar,
    required this.section,
    required this.secGroup,
    required this.loginSource,
    required this.updatedAt,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    map['username'] = Variable<String>(username);
    map['position'] = Variable<String>(position);
    map['fname'] = Variable<String>(fname);
    map['lname'] = Variable<String>(lname);
    if (!nullToAbsent || email != null) {
      map['email'] = Variable<String>(email);
    }
    if (!nullToAbsent || avatar != null) {
      map['avatar'] = Variable<String>(avatar);
    }
    map['section'] = Variable<String>(section);
    map['sec_group'] = Variable<String>(secGroup);
    map['login_source'] = Variable<String>(loginSource);
    map['updated_at'] = Variable<DateTime>(updatedAt);
    return map;
  }

  UserProfileTableCompanion toCompanion(bool nullToAbsent) {
    return UserProfileTableCompanion(
      id: Value(id),
      username: Value(username),
      position: Value(position),
      fname: Value(fname),
      lname: Value(lname),
      email: email == null && nullToAbsent
          ? const Value.absent()
          : Value(email),
      avatar: avatar == null && nullToAbsent
          ? const Value.absent()
          : Value(avatar),
      section: Value(section),
      secGroup: Value(secGroup),
      loginSource: Value(loginSource),
      updatedAt: Value(updatedAt),
    );
  }

  factory UserProfileRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return UserProfileRow(
      id: serializer.fromJson<int>(json['id']),
      username: serializer.fromJson<String>(json['username']),
      position: serializer.fromJson<String>(json['position']),
      fname: serializer.fromJson<String>(json['fname']),
      lname: serializer.fromJson<String>(json['lname']),
      email: serializer.fromJson<String?>(json['email']),
      avatar: serializer.fromJson<String?>(json['avatar']),
      section: serializer.fromJson<String>(json['section']),
      secGroup: serializer.fromJson<String>(json['secGroup']),
      loginSource: serializer.fromJson<String>(json['loginSource']),
      updatedAt: serializer.fromJson<DateTime>(json['updatedAt']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'username': serializer.toJson<String>(username),
      'position': serializer.toJson<String>(position),
      'fname': serializer.toJson<String>(fname),
      'lname': serializer.toJson<String>(lname),
      'email': serializer.toJson<String?>(email),
      'avatar': serializer.toJson<String?>(avatar),
      'section': serializer.toJson<String>(section),
      'secGroup': serializer.toJson<String>(secGroup),
      'loginSource': serializer.toJson<String>(loginSource),
      'updatedAt': serializer.toJson<DateTime>(updatedAt),
    };
  }

  UserProfileRow copyWith({
    int? id,
    String? username,
    String? position,
    String? fname,
    String? lname,
    Value<String?> email = const Value.absent(),
    Value<String?> avatar = const Value.absent(),
    String? section,
    String? secGroup,
    String? loginSource,
    DateTime? updatedAt,
  }) => UserProfileRow(
    id: id ?? this.id,
    username: username ?? this.username,
    position: position ?? this.position,
    fname: fname ?? this.fname,
    lname: lname ?? this.lname,
    email: email.present ? email.value : this.email,
    avatar: avatar.present ? avatar.value : this.avatar,
    section: section ?? this.section,
    secGroup: secGroup ?? this.secGroup,
    loginSource: loginSource ?? this.loginSource,
    updatedAt: updatedAt ?? this.updatedAt,
  );
  UserProfileRow copyWithCompanion(UserProfileTableCompanion data) {
    return UserProfileRow(
      id: data.id.present ? data.id.value : this.id,
      username: data.username.present ? data.username.value : this.username,
      position: data.position.present ? data.position.value : this.position,
      fname: data.fname.present ? data.fname.value : this.fname,
      lname: data.lname.present ? data.lname.value : this.lname,
      email: data.email.present ? data.email.value : this.email,
      avatar: data.avatar.present ? data.avatar.value : this.avatar,
      section: data.section.present ? data.section.value : this.section,
      secGroup: data.secGroup.present ? data.secGroup.value : this.secGroup,
      loginSource: data.loginSource.present
          ? data.loginSource.value
          : this.loginSource,
      updatedAt: data.updatedAt.present ? data.updatedAt.value : this.updatedAt,
    );
  }

  @override
  String toString() {
    return (StringBuffer('UserProfileRow(')
          ..write('id: $id, ')
          ..write('username: $username, ')
          ..write('position: $position, ')
          ..write('fname: $fname, ')
          ..write('lname: $lname, ')
          ..write('email: $email, ')
          ..write('avatar: $avatar, ')
          ..write('section: $section, ')
          ..write('secGroup: $secGroup, ')
          ..write('loginSource: $loginSource, ')
          ..write('updatedAt: $updatedAt')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    username,
    position,
    fname,
    lname,
    email,
    avatar,
    section,
    secGroup,
    loginSource,
    updatedAt,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is UserProfileRow &&
          other.id == this.id &&
          other.username == this.username &&
          other.position == this.position &&
          other.fname == this.fname &&
          other.lname == this.lname &&
          other.email == this.email &&
          other.avatar == this.avatar &&
          other.section == this.section &&
          other.secGroup == this.secGroup &&
          other.loginSource == this.loginSource &&
          other.updatedAt == this.updatedAt);
}

class UserProfileTableCompanion extends UpdateCompanion<UserProfileRow> {
  final Value<int> id;
  final Value<String> username;
  final Value<String> position;
  final Value<String> fname;
  final Value<String> lname;
  final Value<String?> email;
  final Value<String?> avatar;
  final Value<String> section;
  final Value<String> secGroup;
  final Value<String> loginSource;
  final Value<DateTime> updatedAt;
  const UserProfileTableCompanion({
    this.id = const Value.absent(),
    this.username = const Value.absent(),
    this.position = const Value.absent(),
    this.fname = const Value.absent(),
    this.lname = const Value.absent(),
    this.email = const Value.absent(),
    this.avatar = const Value.absent(),
    this.section = const Value.absent(),
    this.secGroup = const Value.absent(),
    this.loginSource = const Value.absent(),
    this.updatedAt = const Value.absent(),
  });
  UserProfileTableCompanion.insert({
    this.id = const Value.absent(),
    required String username,
    this.position = const Value.absent(),
    this.fname = const Value.absent(),
    this.lname = const Value.absent(),
    this.email = const Value.absent(),
    this.avatar = const Value.absent(),
    this.section = const Value.absent(),
    this.secGroup = const Value.absent(),
    this.loginSource = const Value.absent(),
    this.updatedAt = const Value.absent(),
  }) : username = Value(username);
  static Insertable<UserProfileRow> custom({
    Expression<int>? id,
    Expression<String>? username,
    Expression<String>? position,
    Expression<String>? fname,
    Expression<String>? lname,
    Expression<String>? email,
    Expression<String>? avatar,
    Expression<String>? section,
    Expression<String>? secGroup,
    Expression<String>? loginSource,
    Expression<DateTime>? updatedAt,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (username != null) 'username': username,
      if (position != null) 'position': position,
      if (fname != null) 'fname': fname,
      if (lname != null) 'lname': lname,
      if (email != null) 'email': email,
      if (avatar != null) 'avatar': avatar,
      if (section != null) 'section': section,
      if (secGroup != null) 'sec_group': secGroup,
      if (loginSource != null) 'login_source': loginSource,
      if (updatedAt != null) 'updated_at': updatedAt,
    });
  }

  UserProfileTableCompanion copyWith({
    Value<int>? id,
    Value<String>? username,
    Value<String>? position,
    Value<String>? fname,
    Value<String>? lname,
    Value<String?>? email,
    Value<String?>? avatar,
    Value<String>? section,
    Value<String>? secGroup,
    Value<String>? loginSource,
    Value<DateTime>? updatedAt,
  }) {
    return UserProfileTableCompanion(
      id: id ?? this.id,
      username: username ?? this.username,
      position: position ?? this.position,
      fname: fname ?? this.fname,
      lname: lname ?? this.lname,
      email: email ?? this.email,
      avatar: avatar ?? this.avatar,
      section: section ?? this.section,
      secGroup: secGroup ?? this.secGroup,
      loginSource: loginSource ?? this.loginSource,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (username.present) {
      map['username'] = Variable<String>(username.value);
    }
    if (position.present) {
      map['position'] = Variable<String>(position.value);
    }
    if (fname.present) {
      map['fname'] = Variable<String>(fname.value);
    }
    if (lname.present) {
      map['lname'] = Variable<String>(lname.value);
    }
    if (email.present) {
      map['email'] = Variable<String>(email.value);
    }
    if (avatar.present) {
      map['avatar'] = Variable<String>(avatar.value);
    }
    if (section.present) {
      map['section'] = Variable<String>(section.value);
    }
    if (secGroup.present) {
      map['sec_group'] = Variable<String>(secGroup.value);
    }
    if (loginSource.present) {
      map['login_source'] = Variable<String>(loginSource.value);
    }
    if (updatedAt.present) {
      map['updated_at'] = Variable<DateTime>(updatedAt.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('UserProfileTableCompanion(')
          ..write('id: $id, ')
          ..write('username: $username, ')
          ..write('position: $position, ')
          ..write('fname: $fname, ')
          ..write('lname: $lname, ')
          ..write('email: $email, ')
          ..write('avatar: $avatar, ')
          ..write('section: $section, ')
          ..write('secGroup: $secGroup, ')
          ..write('loginSource: $loginSource, ')
          ..write('updatedAt: $updatedAt')
          ..write(')'))
        .toString();
  }
}

class $MemoTableTable extends MemoTable
    with TableInfo<$MemoTableTable, MemoRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $MemoTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    hasAutoIncrement: true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'PRIMARY KEY AUTOINCREMENT',
    ),
  );
  static const VerificationMeta _serverIdMeta = const VerificationMeta(
    'serverId',
  );
  @override
  late final GeneratedColumn<int> serverId = GeneratedColumn<int>(
    'server_id',
    aliasedName,
    true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _titleMeta = const VerificationMeta('title');
  @override
  late final GeneratedColumn<String> title = GeneratedColumn<String>(
    'title',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _bodyMeta = const VerificationMeta('body');
  @override
  late final GeneratedColumn<String> body = GeneratedColumn<String>(
    'body',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _attachmentMeta = const VerificationMeta(
    'attachment',
  );
  @override
  late final GeneratedColumn<String> attachment = GeneratedColumn<String>(
    'attachment',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _createdAtMeta = const VerificationMeta(
    'createdAt',
  );
  @override
  late final GeneratedColumn<DateTime> createdAt = GeneratedColumn<DateTime>(
    'created_at',
    aliasedName,
    true,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _updatedAtMeta = const VerificationMeta(
    'updatedAt',
  );
  @override
  late final GeneratedColumn<DateTime> updatedAt = GeneratedColumn<DateTime>(
    'updated_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  static const VerificationMeta _isDirtyMeta = const VerificationMeta(
    'isDirty',
  );
  @override
  late final GeneratedColumn<bool> isDirty = GeneratedColumn<bool>(
    'is_dirty',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_dirty" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  static const VerificationMeta _isDeletedMeta = const VerificationMeta(
    'isDeleted',
  );
  @override
  late final GeneratedColumn<bool> isDeleted = GeneratedColumn<bool>(
    'is_deleted',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_deleted" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    serverId,
    title,
    body,
    attachment,
    createdAt,
    updatedAt,
    isDirty,
    isDeleted,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'memo_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<MemoRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('server_id')) {
      context.handle(
        _serverIdMeta,
        serverId.isAcceptableOrUnknown(data['server_id']!, _serverIdMeta),
      );
    }
    if (data.containsKey('title')) {
      context.handle(
        _titleMeta,
        title.isAcceptableOrUnknown(data['title']!, _titleMeta),
      );
    } else if (isInserting) {
      context.missing(_titleMeta);
    }
    if (data.containsKey('body')) {
      context.handle(
        _bodyMeta,
        body.isAcceptableOrUnknown(data['body']!, _bodyMeta),
      );
    }
    if (data.containsKey('attachment')) {
      context.handle(
        _attachmentMeta,
        attachment.isAcceptableOrUnknown(data['attachment']!, _attachmentMeta),
      );
    }
    if (data.containsKey('created_at')) {
      context.handle(
        _createdAtMeta,
        createdAt.isAcceptableOrUnknown(data['created_at']!, _createdAtMeta),
      );
    }
    if (data.containsKey('updated_at')) {
      context.handle(
        _updatedAtMeta,
        updatedAt.isAcceptableOrUnknown(data['updated_at']!, _updatedAtMeta),
      );
    }
    if (data.containsKey('is_dirty')) {
      context.handle(
        _isDirtyMeta,
        isDirty.isAcceptableOrUnknown(data['is_dirty']!, _isDirtyMeta),
      );
    }
    if (data.containsKey('is_deleted')) {
      context.handle(
        _isDeletedMeta,
        isDeleted.isAcceptableOrUnknown(data['is_deleted']!, _isDeletedMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  MemoRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return MemoRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      serverId: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}server_id'],
      ),
      title: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}title'],
      )!,
      body: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}body'],
      )!,
      attachment: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}attachment'],
      ),
      createdAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}created_at'],
      ),
      updatedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}updated_at'],
      )!,
      isDirty: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_dirty'],
      )!,
      isDeleted: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_deleted'],
      )!,
    );
  }

  @override
  $MemoTableTable createAlias(String alias) {
    return $MemoTableTable(attachedDatabase, alias);
  }
}

class MemoRow extends DataClass implements Insertable<MemoRow> {
  final int id;
  final int? serverId;
  final String title;
  final String body;
  final String? attachment;
  final DateTime? createdAt;
  final DateTime updatedAt;
  final bool isDirty;
  final bool isDeleted;
  const MemoRow({
    required this.id,
    this.serverId,
    required this.title,
    required this.body,
    this.attachment,
    this.createdAt,
    required this.updatedAt,
    required this.isDirty,
    required this.isDeleted,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    if (!nullToAbsent || serverId != null) {
      map['server_id'] = Variable<int>(serverId);
    }
    map['title'] = Variable<String>(title);
    map['body'] = Variable<String>(body);
    if (!nullToAbsent || attachment != null) {
      map['attachment'] = Variable<String>(attachment);
    }
    if (!nullToAbsent || createdAt != null) {
      map['created_at'] = Variable<DateTime>(createdAt);
    }
    map['updated_at'] = Variable<DateTime>(updatedAt);
    map['is_dirty'] = Variable<bool>(isDirty);
    map['is_deleted'] = Variable<bool>(isDeleted);
    return map;
  }

  MemoTableCompanion toCompanion(bool nullToAbsent) {
    return MemoTableCompanion(
      id: Value(id),
      serverId: serverId == null && nullToAbsent
          ? const Value.absent()
          : Value(serverId),
      title: Value(title),
      body: Value(body),
      attachment: attachment == null && nullToAbsent
          ? const Value.absent()
          : Value(attachment),
      createdAt: createdAt == null && nullToAbsent
          ? const Value.absent()
          : Value(createdAt),
      updatedAt: Value(updatedAt),
      isDirty: Value(isDirty),
      isDeleted: Value(isDeleted),
    );
  }

  factory MemoRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return MemoRow(
      id: serializer.fromJson<int>(json['id']),
      serverId: serializer.fromJson<int?>(json['serverId']),
      title: serializer.fromJson<String>(json['title']),
      body: serializer.fromJson<String>(json['body']),
      attachment: serializer.fromJson<String?>(json['attachment']),
      createdAt: serializer.fromJson<DateTime?>(json['createdAt']),
      updatedAt: serializer.fromJson<DateTime>(json['updatedAt']),
      isDirty: serializer.fromJson<bool>(json['isDirty']),
      isDeleted: serializer.fromJson<bool>(json['isDeleted']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'serverId': serializer.toJson<int?>(serverId),
      'title': serializer.toJson<String>(title),
      'body': serializer.toJson<String>(body),
      'attachment': serializer.toJson<String?>(attachment),
      'createdAt': serializer.toJson<DateTime?>(createdAt),
      'updatedAt': serializer.toJson<DateTime>(updatedAt),
      'isDirty': serializer.toJson<bool>(isDirty),
      'isDeleted': serializer.toJson<bool>(isDeleted),
    };
  }

  MemoRow copyWith({
    int? id,
    Value<int?> serverId = const Value.absent(),
    String? title,
    String? body,
    Value<String?> attachment = const Value.absent(),
    Value<DateTime?> createdAt = const Value.absent(),
    DateTime? updatedAt,
    bool? isDirty,
    bool? isDeleted,
  }) => MemoRow(
    id: id ?? this.id,
    serverId: serverId.present ? serverId.value : this.serverId,
    title: title ?? this.title,
    body: body ?? this.body,
    attachment: attachment.present ? attachment.value : this.attachment,
    createdAt: createdAt.present ? createdAt.value : this.createdAt,
    updatedAt: updatedAt ?? this.updatedAt,
    isDirty: isDirty ?? this.isDirty,
    isDeleted: isDeleted ?? this.isDeleted,
  );
  MemoRow copyWithCompanion(MemoTableCompanion data) {
    return MemoRow(
      id: data.id.present ? data.id.value : this.id,
      serverId: data.serverId.present ? data.serverId.value : this.serverId,
      title: data.title.present ? data.title.value : this.title,
      body: data.body.present ? data.body.value : this.body,
      attachment: data.attachment.present
          ? data.attachment.value
          : this.attachment,
      createdAt: data.createdAt.present ? data.createdAt.value : this.createdAt,
      updatedAt: data.updatedAt.present ? data.updatedAt.value : this.updatedAt,
      isDirty: data.isDirty.present ? data.isDirty.value : this.isDirty,
      isDeleted: data.isDeleted.present ? data.isDeleted.value : this.isDeleted,
    );
  }

  @override
  String toString() {
    return (StringBuffer('MemoRow(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('title: $title, ')
          ..write('body: $body, ')
          ..write('attachment: $attachment, ')
          ..write('createdAt: $createdAt, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    serverId,
    title,
    body,
    attachment,
    createdAt,
    updatedAt,
    isDirty,
    isDeleted,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is MemoRow &&
          other.id == this.id &&
          other.serverId == this.serverId &&
          other.title == this.title &&
          other.body == this.body &&
          other.attachment == this.attachment &&
          other.createdAt == this.createdAt &&
          other.updatedAt == this.updatedAt &&
          other.isDirty == this.isDirty &&
          other.isDeleted == this.isDeleted);
}

class MemoTableCompanion extends UpdateCompanion<MemoRow> {
  final Value<int> id;
  final Value<int?> serverId;
  final Value<String> title;
  final Value<String> body;
  final Value<String?> attachment;
  final Value<DateTime?> createdAt;
  final Value<DateTime> updatedAt;
  final Value<bool> isDirty;
  final Value<bool> isDeleted;
  const MemoTableCompanion({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    this.title = const Value.absent(),
    this.body = const Value.absent(),
    this.attachment = const Value.absent(),
    this.createdAt = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  });
  MemoTableCompanion.insert({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    required String title,
    this.body = const Value.absent(),
    this.attachment = const Value.absent(),
    this.createdAt = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  }) : title = Value(title);
  static Insertable<MemoRow> custom({
    Expression<int>? id,
    Expression<int>? serverId,
    Expression<String>? title,
    Expression<String>? body,
    Expression<String>? attachment,
    Expression<DateTime>? createdAt,
    Expression<DateTime>? updatedAt,
    Expression<bool>? isDirty,
    Expression<bool>? isDeleted,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (serverId != null) 'server_id': serverId,
      if (title != null) 'title': title,
      if (body != null) 'body': body,
      if (attachment != null) 'attachment': attachment,
      if (createdAt != null) 'created_at': createdAt,
      if (updatedAt != null) 'updated_at': updatedAt,
      if (isDirty != null) 'is_dirty': isDirty,
      if (isDeleted != null) 'is_deleted': isDeleted,
    });
  }

  MemoTableCompanion copyWith({
    Value<int>? id,
    Value<int?>? serverId,
    Value<String>? title,
    Value<String>? body,
    Value<String?>? attachment,
    Value<DateTime?>? createdAt,
    Value<DateTime>? updatedAt,
    Value<bool>? isDirty,
    Value<bool>? isDeleted,
  }) {
    return MemoTableCompanion(
      id: id ?? this.id,
      serverId: serverId ?? this.serverId,
      title: title ?? this.title,
      body: body ?? this.body,
      attachment: attachment ?? this.attachment,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      isDirty: isDirty ?? this.isDirty,
      isDeleted: isDeleted ?? this.isDeleted,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (serverId.present) {
      map['server_id'] = Variable<int>(serverId.value);
    }
    if (title.present) {
      map['title'] = Variable<String>(title.value);
    }
    if (body.present) {
      map['body'] = Variable<String>(body.value);
    }
    if (attachment.present) {
      map['attachment'] = Variable<String>(attachment.value);
    }
    if (createdAt.present) {
      map['created_at'] = Variable<DateTime>(createdAt.value);
    }
    if (updatedAt.present) {
      map['updated_at'] = Variable<DateTime>(updatedAt.value);
    }
    if (isDirty.present) {
      map['is_dirty'] = Variable<bool>(isDirty.value);
    }
    if (isDeleted.present) {
      map['is_deleted'] = Variable<bool>(isDeleted.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('MemoTableCompanion(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('title: $title, ')
          ..write('body: $body, ')
          ..write('attachment: $attachment, ')
          ..write('createdAt: $createdAt, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }
}

class $AccomplishmentTableTable extends AccomplishmentTable
    with TableInfo<$AccomplishmentTableTable, AccomplishmentRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $AccomplishmentTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    hasAutoIncrement: true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'PRIMARY KEY AUTOINCREMENT',
    ),
  );
  static const VerificationMeta _serverIdMeta = const VerificationMeta(
    'serverId',
  );
  @override
  late final GeneratedColumn<int> serverId = GeneratedColumn<int>(
    'server_id',
    aliasedName,
    true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _titleMeta = const VerificationMeta('title');
  @override
  late final GeneratedColumn<String> title = GeneratedColumn<String>(
    'title',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _sectionMeta = const VerificationMeta(
    'section',
  );
  @override
  late final GeneratedColumn<String> section = GeneratedColumn<String>(
    'section',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _statusMeta = const VerificationMeta('status');
  @override
  late final GeneratedColumn<String> status = GeneratedColumn<String>(
    'status',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant('draft'),
  );
  static const VerificationMeta _detailsMeta = const VerificationMeta(
    'details',
  );
  @override
  late final GeneratedColumn<String> details = GeneratedColumn<String>(
    'details',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _dateMeta = const VerificationMeta('date');
  @override
  late final GeneratedColumn<DateTime> date = GeneratedColumn<DateTime>(
    'date',
    aliasedName,
    true,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _updatedAtMeta = const VerificationMeta(
    'updatedAt',
  );
  @override
  late final GeneratedColumn<DateTime> updatedAt = GeneratedColumn<DateTime>(
    'updated_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  static const VerificationMeta _isDirtyMeta = const VerificationMeta(
    'isDirty',
  );
  @override
  late final GeneratedColumn<bool> isDirty = GeneratedColumn<bool>(
    'is_dirty',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_dirty" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  static const VerificationMeta _isDeletedMeta = const VerificationMeta(
    'isDeleted',
  );
  @override
  late final GeneratedColumn<bool> isDeleted = GeneratedColumn<bool>(
    'is_deleted',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_deleted" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    serverId,
    title,
    section,
    status,
    details,
    date,
    updatedAt,
    isDirty,
    isDeleted,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'accomplishment_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<AccomplishmentRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('server_id')) {
      context.handle(
        _serverIdMeta,
        serverId.isAcceptableOrUnknown(data['server_id']!, _serverIdMeta),
      );
    }
    if (data.containsKey('title')) {
      context.handle(
        _titleMeta,
        title.isAcceptableOrUnknown(data['title']!, _titleMeta),
      );
    } else if (isInserting) {
      context.missing(_titleMeta);
    }
    if (data.containsKey('section')) {
      context.handle(
        _sectionMeta,
        section.isAcceptableOrUnknown(data['section']!, _sectionMeta),
      );
    }
    if (data.containsKey('status')) {
      context.handle(
        _statusMeta,
        status.isAcceptableOrUnknown(data['status']!, _statusMeta),
      );
    }
    if (data.containsKey('details')) {
      context.handle(
        _detailsMeta,
        details.isAcceptableOrUnknown(data['details']!, _detailsMeta),
      );
    }
    if (data.containsKey('date')) {
      context.handle(
        _dateMeta,
        date.isAcceptableOrUnknown(data['date']!, _dateMeta),
      );
    }
    if (data.containsKey('updated_at')) {
      context.handle(
        _updatedAtMeta,
        updatedAt.isAcceptableOrUnknown(data['updated_at']!, _updatedAtMeta),
      );
    }
    if (data.containsKey('is_dirty')) {
      context.handle(
        _isDirtyMeta,
        isDirty.isAcceptableOrUnknown(data['is_dirty']!, _isDirtyMeta),
      );
    }
    if (data.containsKey('is_deleted')) {
      context.handle(
        _isDeletedMeta,
        isDeleted.isAcceptableOrUnknown(data['is_deleted']!, _isDeletedMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  AccomplishmentRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return AccomplishmentRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      serverId: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}server_id'],
      ),
      title: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}title'],
      )!,
      section: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}section'],
      )!,
      status: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}status'],
      )!,
      details: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}details'],
      )!,
      date: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}date'],
      ),
      updatedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}updated_at'],
      )!,
      isDirty: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_dirty'],
      )!,
      isDeleted: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_deleted'],
      )!,
    );
  }

  @override
  $AccomplishmentTableTable createAlias(String alias) {
    return $AccomplishmentTableTable(attachedDatabase, alias);
  }
}

class AccomplishmentRow extends DataClass
    implements Insertable<AccomplishmentRow> {
  final int id;
  final int? serverId;
  final String title;
  final String section;
  final String status;
  final String details;
  final DateTime? date;
  final DateTime updatedAt;
  final bool isDirty;
  final bool isDeleted;
  const AccomplishmentRow({
    required this.id,
    this.serverId,
    required this.title,
    required this.section,
    required this.status,
    required this.details,
    this.date,
    required this.updatedAt,
    required this.isDirty,
    required this.isDeleted,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    if (!nullToAbsent || serverId != null) {
      map['server_id'] = Variable<int>(serverId);
    }
    map['title'] = Variable<String>(title);
    map['section'] = Variable<String>(section);
    map['status'] = Variable<String>(status);
    map['details'] = Variable<String>(details);
    if (!nullToAbsent || date != null) {
      map['date'] = Variable<DateTime>(date);
    }
    map['updated_at'] = Variable<DateTime>(updatedAt);
    map['is_dirty'] = Variable<bool>(isDirty);
    map['is_deleted'] = Variable<bool>(isDeleted);
    return map;
  }

  AccomplishmentTableCompanion toCompanion(bool nullToAbsent) {
    return AccomplishmentTableCompanion(
      id: Value(id),
      serverId: serverId == null && nullToAbsent
          ? const Value.absent()
          : Value(serverId),
      title: Value(title),
      section: Value(section),
      status: Value(status),
      details: Value(details),
      date: date == null && nullToAbsent ? const Value.absent() : Value(date),
      updatedAt: Value(updatedAt),
      isDirty: Value(isDirty),
      isDeleted: Value(isDeleted),
    );
  }

  factory AccomplishmentRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return AccomplishmentRow(
      id: serializer.fromJson<int>(json['id']),
      serverId: serializer.fromJson<int?>(json['serverId']),
      title: serializer.fromJson<String>(json['title']),
      section: serializer.fromJson<String>(json['section']),
      status: serializer.fromJson<String>(json['status']),
      details: serializer.fromJson<String>(json['details']),
      date: serializer.fromJson<DateTime?>(json['date']),
      updatedAt: serializer.fromJson<DateTime>(json['updatedAt']),
      isDirty: serializer.fromJson<bool>(json['isDirty']),
      isDeleted: serializer.fromJson<bool>(json['isDeleted']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'serverId': serializer.toJson<int?>(serverId),
      'title': serializer.toJson<String>(title),
      'section': serializer.toJson<String>(section),
      'status': serializer.toJson<String>(status),
      'details': serializer.toJson<String>(details),
      'date': serializer.toJson<DateTime?>(date),
      'updatedAt': serializer.toJson<DateTime>(updatedAt),
      'isDirty': serializer.toJson<bool>(isDirty),
      'isDeleted': serializer.toJson<bool>(isDeleted),
    };
  }

  AccomplishmentRow copyWith({
    int? id,
    Value<int?> serverId = const Value.absent(),
    String? title,
    String? section,
    String? status,
    String? details,
    Value<DateTime?> date = const Value.absent(),
    DateTime? updatedAt,
    bool? isDirty,
    bool? isDeleted,
  }) => AccomplishmentRow(
    id: id ?? this.id,
    serverId: serverId.present ? serverId.value : this.serverId,
    title: title ?? this.title,
    section: section ?? this.section,
    status: status ?? this.status,
    details: details ?? this.details,
    date: date.present ? date.value : this.date,
    updatedAt: updatedAt ?? this.updatedAt,
    isDirty: isDirty ?? this.isDirty,
    isDeleted: isDeleted ?? this.isDeleted,
  );
  AccomplishmentRow copyWithCompanion(AccomplishmentTableCompanion data) {
    return AccomplishmentRow(
      id: data.id.present ? data.id.value : this.id,
      serverId: data.serverId.present ? data.serverId.value : this.serverId,
      title: data.title.present ? data.title.value : this.title,
      section: data.section.present ? data.section.value : this.section,
      status: data.status.present ? data.status.value : this.status,
      details: data.details.present ? data.details.value : this.details,
      date: data.date.present ? data.date.value : this.date,
      updatedAt: data.updatedAt.present ? data.updatedAt.value : this.updatedAt,
      isDirty: data.isDirty.present ? data.isDirty.value : this.isDirty,
      isDeleted: data.isDeleted.present ? data.isDeleted.value : this.isDeleted,
    );
  }

  @override
  String toString() {
    return (StringBuffer('AccomplishmentRow(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('title: $title, ')
          ..write('section: $section, ')
          ..write('status: $status, ')
          ..write('details: $details, ')
          ..write('date: $date, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    serverId,
    title,
    section,
    status,
    details,
    date,
    updatedAt,
    isDirty,
    isDeleted,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is AccomplishmentRow &&
          other.id == this.id &&
          other.serverId == this.serverId &&
          other.title == this.title &&
          other.section == this.section &&
          other.status == this.status &&
          other.details == this.details &&
          other.date == this.date &&
          other.updatedAt == this.updatedAt &&
          other.isDirty == this.isDirty &&
          other.isDeleted == this.isDeleted);
}

class AccomplishmentTableCompanion extends UpdateCompanion<AccomplishmentRow> {
  final Value<int> id;
  final Value<int?> serverId;
  final Value<String> title;
  final Value<String> section;
  final Value<String> status;
  final Value<String> details;
  final Value<DateTime?> date;
  final Value<DateTime> updatedAt;
  final Value<bool> isDirty;
  final Value<bool> isDeleted;
  const AccomplishmentTableCompanion({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    this.title = const Value.absent(),
    this.section = const Value.absent(),
    this.status = const Value.absent(),
    this.details = const Value.absent(),
    this.date = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  });
  AccomplishmentTableCompanion.insert({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    required String title,
    this.section = const Value.absent(),
    this.status = const Value.absent(),
    this.details = const Value.absent(),
    this.date = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  }) : title = Value(title);
  static Insertable<AccomplishmentRow> custom({
    Expression<int>? id,
    Expression<int>? serverId,
    Expression<String>? title,
    Expression<String>? section,
    Expression<String>? status,
    Expression<String>? details,
    Expression<DateTime>? date,
    Expression<DateTime>? updatedAt,
    Expression<bool>? isDirty,
    Expression<bool>? isDeleted,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (serverId != null) 'server_id': serverId,
      if (title != null) 'title': title,
      if (section != null) 'section': section,
      if (status != null) 'status': status,
      if (details != null) 'details': details,
      if (date != null) 'date': date,
      if (updatedAt != null) 'updated_at': updatedAt,
      if (isDirty != null) 'is_dirty': isDirty,
      if (isDeleted != null) 'is_deleted': isDeleted,
    });
  }

  AccomplishmentTableCompanion copyWith({
    Value<int>? id,
    Value<int?>? serverId,
    Value<String>? title,
    Value<String>? section,
    Value<String>? status,
    Value<String>? details,
    Value<DateTime?>? date,
    Value<DateTime>? updatedAt,
    Value<bool>? isDirty,
    Value<bool>? isDeleted,
  }) {
    return AccomplishmentTableCompanion(
      id: id ?? this.id,
      serverId: serverId ?? this.serverId,
      title: title ?? this.title,
      section: section ?? this.section,
      status: status ?? this.status,
      details: details ?? this.details,
      date: date ?? this.date,
      updatedAt: updatedAt ?? this.updatedAt,
      isDirty: isDirty ?? this.isDirty,
      isDeleted: isDeleted ?? this.isDeleted,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (serverId.present) {
      map['server_id'] = Variable<int>(serverId.value);
    }
    if (title.present) {
      map['title'] = Variable<String>(title.value);
    }
    if (section.present) {
      map['section'] = Variable<String>(section.value);
    }
    if (status.present) {
      map['status'] = Variable<String>(status.value);
    }
    if (details.present) {
      map['details'] = Variable<String>(details.value);
    }
    if (date.present) {
      map['date'] = Variable<DateTime>(date.value);
    }
    if (updatedAt.present) {
      map['updated_at'] = Variable<DateTime>(updatedAt.value);
    }
    if (isDirty.present) {
      map['is_dirty'] = Variable<bool>(isDirty.value);
    }
    if (isDeleted.present) {
      map['is_deleted'] = Variable<bool>(isDeleted.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('AccomplishmentTableCompanion(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('title: $title, ')
          ..write('section: $section, ')
          ..write('status: $status, ')
          ..write('details: $details, ')
          ..write('date: $date, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }
}

class $SchoolTableTable extends SchoolTable
    with TableInfo<$SchoolTableTable, SchoolRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $SchoolTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    hasAutoIncrement: true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'PRIMARY KEY AUTOINCREMENT',
    ),
  );
  static const VerificationMeta _schoolIdMeta = const VerificationMeta(
    'schoolId',
  );
  @override
  late final GeneratedColumn<String> schoolId = GeneratedColumn<String>(
    'school_id',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _schoolNameMeta = const VerificationMeta(
    'schoolName',
  );
  @override
  late final GeneratedColumn<String> schoolName = GeneratedColumn<String>(
    'school_name',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _districtMeta = const VerificationMeta(
    'district',
  );
  @override
  late final GeneratedColumn<String> district = GeneratedColumn<String>(
    'district',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _divisionMeta = const VerificationMeta(
    'division',
  );
  @override
  late final GeneratedColumn<String> division = GeneratedColumn<String>(
    'division',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _schoolEmailMeta = const VerificationMeta(
    'schoolEmail',
  );
  @override
  late final GeneratedColumn<String> schoolEmail = GeneratedColumn<String>(
    'school_email',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _schoolTypeMeta = const VerificationMeta(
    'schoolType',
  );
  @override
  late final GeneratedColumn<String> schoolType = GeneratedColumn<String>(
    'school_type',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant(''),
  );
  static const VerificationMeta _updatedAtMeta = const VerificationMeta(
    'updatedAt',
  );
  @override
  late final GeneratedColumn<DateTime> updatedAt = GeneratedColumn<DateTime>(
    'updated_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    schoolId,
    schoolName,
    district,
    division,
    schoolEmail,
    schoolType,
    updatedAt,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'school_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<SchoolRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('school_id')) {
      context.handle(
        _schoolIdMeta,
        schoolId.isAcceptableOrUnknown(data['school_id']!, _schoolIdMeta),
      );
    } else if (isInserting) {
      context.missing(_schoolIdMeta);
    }
    if (data.containsKey('school_name')) {
      context.handle(
        _schoolNameMeta,
        schoolName.isAcceptableOrUnknown(data['school_name']!, _schoolNameMeta),
      );
    } else if (isInserting) {
      context.missing(_schoolNameMeta);
    }
    if (data.containsKey('district')) {
      context.handle(
        _districtMeta,
        district.isAcceptableOrUnknown(data['district']!, _districtMeta),
      );
    }
    if (data.containsKey('division')) {
      context.handle(
        _divisionMeta,
        division.isAcceptableOrUnknown(data['division']!, _divisionMeta),
      );
    }
    if (data.containsKey('school_email')) {
      context.handle(
        _schoolEmailMeta,
        schoolEmail.isAcceptableOrUnknown(
          data['school_email']!,
          _schoolEmailMeta,
        ),
      );
    }
    if (data.containsKey('school_type')) {
      context.handle(
        _schoolTypeMeta,
        schoolType.isAcceptableOrUnknown(data['school_type']!, _schoolTypeMeta),
      );
    }
    if (data.containsKey('updated_at')) {
      context.handle(
        _updatedAtMeta,
        updatedAt.isAcceptableOrUnknown(data['updated_at']!, _updatedAtMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  SchoolRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return SchoolRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      schoolId: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}school_id'],
      )!,
      schoolName: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}school_name'],
      )!,
      district: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}district'],
      )!,
      division: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}division'],
      )!,
      schoolEmail: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}school_email'],
      ),
      schoolType: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}school_type'],
      )!,
      updatedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}updated_at'],
      )!,
    );
  }

  @override
  $SchoolTableTable createAlias(String alias) {
    return $SchoolTableTable(attachedDatabase, alias);
  }
}

class SchoolRow extends DataClass implements Insertable<SchoolRow> {
  final int id;
  final String schoolId;
  final String schoolName;
  final String district;
  final String division;
  final String? schoolEmail;
  final String schoolType;
  final DateTime updatedAt;
  const SchoolRow({
    required this.id,
    required this.schoolId,
    required this.schoolName,
    required this.district,
    required this.division,
    this.schoolEmail,
    required this.schoolType,
    required this.updatedAt,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    map['school_id'] = Variable<String>(schoolId);
    map['school_name'] = Variable<String>(schoolName);
    map['district'] = Variable<String>(district);
    map['division'] = Variable<String>(division);
    if (!nullToAbsent || schoolEmail != null) {
      map['school_email'] = Variable<String>(schoolEmail);
    }
    map['school_type'] = Variable<String>(schoolType);
    map['updated_at'] = Variable<DateTime>(updatedAt);
    return map;
  }

  SchoolTableCompanion toCompanion(bool nullToAbsent) {
    return SchoolTableCompanion(
      id: Value(id),
      schoolId: Value(schoolId),
      schoolName: Value(schoolName),
      district: Value(district),
      division: Value(division),
      schoolEmail: schoolEmail == null && nullToAbsent
          ? const Value.absent()
          : Value(schoolEmail),
      schoolType: Value(schoolType),
      updatedAt: Value(updatedAt),
    );
  }

  factory SchoolRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return SchoolRow(
      id: serializer.fromJson<int>(json['id']),
      schoolId: serializer.fromJson<String>(json['schoolId']),
      schoolName: serializer.fromJson<String>(json['schoolName']),
      district: serializer.fromJson<String>(json['district']),
      division: serializer.fromJson<String>(json['division']),
      schoolEmail: serializer.fromJson<String?>(json['schoolEmail']),
      schoolType: serializer.fromJson<String>(json['schoolType']),
      updatedAt: serializer.fromJson<DateTime>(json['updatedAt']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'schoolId': serializer.toJson<String>(schoolId),
      'schoolName': serializer.toJson<String>(schoolName),
      'district': serializer.toJson<String>(district),
      'division': serializer.toJson<String>(division),
      'schoolEmail': serializer.toJson<String?>(schoolEmail),
      'schoolType': serializer.toJson<String>(schoolType),
      'updatedAt': serializer.toJson<DateTime>(updatedAt),
    };
  }

  SchoolRow copyWith({
    int? id,
    String? schoolId,
    String? schoolName,
    String? district,
    String? division,
    Value<String?> schoolEmail = const Value.absent(),
    String? schoolType,
    DateTime? updatedAt,
  }) => SchoolRow(
    id: id ?? this.id,
    schoolId: schoolId ?? this.schoolId,
    schoolName: schoolName ?? this.schoolName,
    district: district ?? this.district,
    division: division ?? this.division,
    schoolEmail: schoolEmail.present ? schoolEmail.value : this.schoolEmail,
    schoolType: schoolType ?? this.schoolType,
    updatedAt: updatedAt ?? this.updatedAt,
  );
  SchoolRow copyWithCompanion(SchoolTableCompanion data) {
    return SchoolRow(
      id: data.id.present ? data.id.value : this.id,
      schoolId: data.schoolId.present ? data.schoolId.value : this.schoolId,
      schoolName: data.schoolName.present
          ? data.schoolName.value
          : this.schoolName,
      district: data.district.present ? data.district.value : this.district,
      division: data.division.present ? data.division.value : this.division,
      schoolEmail: data.schoolEmail.present
          ? data.schoolEmail.value
          : this.schoolEmail,
      schoolType: data.schoolType.present
          ? data.schoolType.value
          : this.schoolType,
      updatedAt: data.updatedAt.present ? data.updatedAt.value : this.updatedAt,
    );
  }

  @override
  String toString() {
    return (StringBuffer('SchoolRow(')
          ..write('id: $id, ')
          ..write('schoolId: $schoolId, ')
          ..write('schoolName: $schoolName, ')
          ..write('district: $district, ')
          ..write('division: $division, ')
          ..write('schoolEmail: $schoolEmail, ')
          ..write('schoolType: $schoolType, ')
          ..write('updatedAt: $updatedAt')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    schoolId,
    schoolName,
    district,
    division,
    schoolEmail,
    schoolType,
    updatedAt,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is SchoolRow &&
          other.id == this.id &&
          other.schoolId == this.schoolId &&
          other.schoolName == this.schoolName &&
          other.district == this.district &&
          other.division == this.division &&
          other.schoolEmail == this.schoolEmail &&
          other.schoolType == this.schoolType &&
          other.updatedAt == this.updatedAt);
}

class SchoolTableCompanion extends UpdateCompanion<SchoolRow> {
  final Value<int> id;
  final Value<String> schoolId;
  final Value<String> schoolName;
  final Value<String> district;
  final Value<String> division;
  final Value<String?> schoolEmail;
  final Value<String> schoolType;
  final Value<DateTime> updatedAt;
  const SchoolTableCompanion({
    this.id = const Value.absent(),
    this.schoolId = const Value.absent(),
    this.schoolName = const Value.absent(),
    this.district = const Value.absent(),
    this.division = const Value.absent(),
    this.schoolEmail = const Value.absent(),
    this.schoolType = const Value.absent(),
    this.updatedAt = const Value.absent(),
  });
  SchoolTableCompanion.insert({
    this.id = const Value.absent(),
    required String schoolId,
    required String schoolName,
    this.district = const Value.absent(),
    this.division = const Value.absent(),
    this.schoolEmail = const Value.absent(),
    this.schoolType = const Value.absent(),
    this.updatedAt = const Value.absent(),
  }) : schoolId = Value(schoolId),
       schoolName = Value(schoolName);
  static Insertable<SchoolRow> custom({
    Expression<int>? id,
    Expression<String>? schoolId,
    Expression<String>? schoolName,
    Expression<String>? district,
    Expression<String>? division,
    Expression<String>? schoolEmail,
    Expression<String>? schoolType,
    Expression<DateTime>? updatedAt,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (schoolId != null) 'school_id': schoolId,
      if (schoolName != null) 'school_name': schoolName,
      if (district != null) 'district': district,
      if (division != null) 'division': division,
      if (schoolEmail != null) 'school_email': schoolEmail,
      if (schoolType != null) 'school_type': schoolType,
      if (updatedAt != null) 'updated_at': updatedAt,
    });
  }

  SchoolTableCompanion copyWith({
    Value<int>? id,
    Value<String>? schoolId,
    Value<String>? schoolName,
    Value<String>? district,
    Value<String>? division,
    Value<String?>? schoolEmail,
    Value<String>? schoolType,
    Value<DateTime>? updatedAt,
  }) {
    return SchoolTableCompanion(
      id: id ?? this.id,
      schoolId: schoolId ?? this.schoolId,
      schoolName: schoolName ?? this.schoolName,
      district: district ?? this.district,
      division: division ?? this.division,
      schoolEmail: schoolEmail ?? this.schoolEmail,
      schoolType: schoolType ?? this.schoolType,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (schoolId.present) {
      map['school_id'] = Variable<String>(schoolId.value);
    }
    if (schoolName.present) {
      map['school_name'] = Variable<String>(schoolName.value);
    }
    if (district.present) {
      map['district'] = Variable<String>(district.value);
    }
    if (division.present) {
      map['division'] = Variable<String>(division.value);
    }
    if (schoolEmail.present) {
      map['school_email'] = Variable<String>(schoolEmail.value);
    }
    if (schoolType.present) {
      map['school_type'] = Variable<String>(schoolType.value);
    }
    if (updatedAt.present) {
      map['updated_at'] = Variable<DateTime>(updatedAt.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('SchoolTableCompanion(')
          ..write('id: $id, ')
          ..write('schoolId: $schoolId, ')
          ..write('schoolName: $schoolName, ')
          ..write('district: $district, ')
          ..write('division: $division, ')
          ..write('schoolEmail: $schoolEmail, ')
          ..write('schoolType: $schoolType, ')
          ..write('updatedAt: $updatedAt')
          ..write(')'))
        .toString();
  }
}

class $SchoolPersonnelTableTable extends SchoolPersonnelTable
    with TableInfo<$SchoolPersonnelTableTable, SchoolPersonnelRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $SchoolPersonnelTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    hasAutoIncrement: true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'PRIMARY KEY AUTOINCREMENT',
    ),
  );
  static const VerificationMeta _serverIdMeta = const VerificationMeta(
    'serverId',
  );
  @override
  late final GeneratedColumn<int> serverId = GeneratedColumn<int>(
    'server_id',
    aliasedName,
    true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _schoolIdMeta = const VerificationMeta(
    'schoolId',
  );
  @override
  late final GeneratedColumn<String> schoolId = GeneratedColumn<String>(
    'school_id',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _fullNameMeta = const VerificationMeta(
    'fullName',
  );
  @override
  late final GeneratedColumn<String> fullName = GeneratedColumn<String>(
    'full_name',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _positionTitleMeta = const VerificationMeta(
    'positionTitle',
  );
  @override
  late final GeneratedColumn<String> positionTitle = GeneratedColumn<String>(
    'position_title',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _personnelTypeMeta = const VerificationMeta(
    'personnelType',
  );
  @override
  late final GeneratedColumn<String> personnelType = GeneratedColumn<String>(
    'personnel_type',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
    defaultValue: const Constant('Teaching'),
  );
  static const VerificationMeta _emailMeta = const VerificationMeta('email');
  @override
  late final GeneratedColumn<String> email = GeneratedColumn<String>(
    'email',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _mobileNoMeta = const VerificationMeta(
    'mobileNo',
  );
  @override
  late final GeneratedColumn<String> mobileNo = GeneratedColumn<String>(
    'mobile_no',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _updatedAtMeta = const VerificationMeta(
    'updatedAt',
  );
  @override
  late final GeneratedColumn<DateTime> updatedAt = GeneratedColumn<DateTime>(
    'updated_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  static const VerificationMeta _isDirtyMeta = const VerificationMeta(
    'isDirty',
  );
  @override
  late final GeneratedColumn<bool> isDirty = GeneratedColumn<bool>(
    'is_dirty',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_dirty" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  static const VerificationMeta _isDeletedMeta = const VerificationMeta(
    'isDeleted',
  );
  @override
  late final GeneratedColumn<bool> isDeleted = GeneratedColumn<bool>(
    'is_deleted',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("is_deleted" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    serverId,
    schoolId,
    fullName,
    positionTitle,
    personnelType,
    email,
    mobileNo,
    updatedAt,
    isDirty,
    isDeleted,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'school_personnel_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<SchoolPersonnelRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('server_id')) {
      context.handle(
        _serverIdMeta,
        serverId.isAcceptableOrUnknown(data['server_id']!, _serverIdMeta),
      );
    }
    if (data.containsKey('school_id')) {
      context.handle(
        _schoolIdMeta,
        schoolId.isAcceptableOrUnknown(data['school_id']!, _schoolIdMeta),
      );
    } else if (isInserting) {
      context.missing(_schoolIdMeta);
    }
    if (data.containsKey('full_name')) {
      context.handle(
        _fullNameMeta,
        fullName.isAcceptableOrUnknown(data['full_name']!, _fullNameMeta),
      );
    } else if (isInserting) {
      context.missing(_fullNameMeta);
    }
    if (data.containsKey('position_title')) {
      context.handle(
        _positionTitleMeta,
        positionTitle.isAcceptableOrUnknown(
          data['position_title']!,
          _positionTitleMeta,
        ),
      );
    }
    if (data.containsKey('personnel_type')) {
      context.handle(
        _personnelTypeMeta,
        personnelType.isAcceptableOrUnknown(
          data['personnel_type']!,
          _personnelTypeMeta,
        ),
      );
    }
    if (data.containsKey('email')) {
      context.handle(
        _emailMeta,
        email.isAcceptableOrUnknown(data['email']!, _emailMeta),
      );
    }
    if (data.containsKey('mobile_no')) {
      context.handle(
        _mobileNoMeta,
        mobileNo.isAcceptableOrUnknown(data['mobile_no']!, _mobileNoMeta),
      );
    }
    if (data.containsKey('updated_at')) {
      context.handle(
        _updatedAtMeta,
        updatedAt.isAcceptableOrUnknown(data['updated_at']!, _updatedAtMeta),
      );
    }
    if (data.containsKey('is_dirty')) {
      context.handle(
        _isDirtyMeta,
        isDirty.isAcceptableOrUnknown(data['is_dirty']!, _isDirtyMeta),
      );
    }
    if (data.containsKey('is_deleted')) {
      context.handle(
        _isDeletedMeta,
        isDeleted.isAcceptableOrUnknown(data['is_deleted']!, _isDeletedMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  SchoolPersonnelRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return SchoolPersonnelRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      serverId: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}server_id'],
      ),
      schoolId: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}school_id'],
      )!,
      fullName: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}full_name'],
      )!,
      positionTitle: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}position_title'],
      ),
      personnelType: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}personnel_type'],
      )!,
      email: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}email'],
      ),
      mobileNo: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}mobile_no'],
      ),
      updatedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}updated_at'],
      )!,
      isDirty: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_dirty'],
      )!,
      isDeleted: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}is_deleted'],
      )!,
    );
  }

  @override
  $SchoolPersonnelTableTable createAlias(String alias) {
    return $SchoolPersonnelTableTable(attachedDatabase, alias);
  }
}

class SchoolPersonnelRow extends DataClass
    implements Insertable<SchoolPersonnelRow> {
  final int id;
  final int? serverId;
  final String schoolId;
  final String fullName;
  final String? positionTitle;
  final String personnelType;
  final String? email;
  final String? mobileNo;
  final DateTime updatedAt;
  final bool isDirty;
  final bool isDeleted;
  const SchoolPersonnelRow({
    required this.id,
    this.serverId,
    required this.schoolId,
    required this.fullName,
    this.positionTitle,
    required this.personnelType,
    this.email,
    this.mobileNo,
    required this.updatedAt,
    required this.isDirty,
    required this.isDeleted,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    if (!nullToAbsent || serverId != null) {
      map['server_id'] = Variable<int>(serverId);
    }
    map['school_id'] = Variable<String>(schoolId);
    map['full_name'] = Variable<String>(fullName);
    if (!nullToAbsent || positionTitle != null) {
      map['position_title'] = Variable<String>(positionTitle);
    }
    map['personnel_type'] = Variable<String>(personnelType);
    if (!nullToAbsent || email != null) {
      map['email'] = Variable<String>(email);
    }
    if (!nullToAbsent || mobileNo != null) {
      map['mobile_no'] = Variable<String>(mobileNo);
    }
    map['updated_at'] = Variable<DateTime>(updatedAt);
    map['is_dirty'] = Variable<bool>(isDirty);
    map['is_deleted'] = Variable<bool>(isDeleted);
    return map;
  }

  SchoolPersonnelTableCompanion toCompanion(bool nullToAbsent) {
    return SchoolPersonnelTableCompanion(
      id: Value(id),
      serverId: serverId == null && nullToAbsent
          ? const Value.absent()
          : Value(serverId),
      schoolId: Value(schoolId),
      fullName: Value(fullName),
      positionTitle: positionTitle == null && nullToAbsent
          ? const Value.absent()
          : Value(positionTitle),
      personnelType: Value(personnelType),
      email: email == null && nullToAbsent
          ? const Value.absent()
          : Value(email),
      mobileNo: mobileNo == null && nullToAbsent
          ? const Value.absent()
          : Value(mobileNo),
      updatedAt: Value(updatedAt),
      isDirty: Value(isDirty),
      isDeleted: Value(isDeleted),
    );
  }

  factory SchoolPersonnelRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return SchoolPersonnelRow(
      id: serializer.fromJson<int>(json['id']),
      serverId: serializer.fromJson<int?>(json['serverId']),
      schoolId: serializer.fromJson<String>(json['schoolId']),
      fullName: serializer.fromJson<String>(json['fullName']),
      positionTitle: serializer.fromJson<String?>(json['positionTitle']),
      personnelType: serializer.fromJson<String>(json['personnelType']),
      email: serializer.fromJson<String?>(json['email']),
      mobileNo: serializer.fromJson<String?>(json['mobileNo']),
      updatedAt: serializer.fromJson<DateTime>(json['updatedAt']),
      isDirty: serializer.fromJson<bool>(json['isDirty']),
      isDeleted: serializer.fromJson<bool>(json['isDeleted']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'serverId': serializer.toJson<int?>(serverId),
      'schoolId': serializer.toJson<String>(schoolId),
      'fullName': serializer.toJson<String>(fullName),
      'positionTitle': serializer.toJson<String?>(positionTitle),
      'personnelType': serializer.toJson<String>(personnelType),
      'email': serializer.toJson<String?>(email),
      'mobileNo': serializer.toJson<String?>(mobileNo),
      'updatedAt': serializer.toJson<DateTime>(updatedAt),
      'isDirty': serializer.toJson<bool>(isDirty),
      'isDeleted': serializer.toJson<bool>(isDeleted),
    };
  }

  SchoolPersonnelRow copyWith({
    int? id,
    Value<int?> serverId = const Value.absent(),
    String? schoolId,
    String? fullName,
    Value<String?> positionTitle = const Value.absent(),
    String? personnelType,
    Value<String?> email = const Value.absent(),
    Value<String?> mobileNo = const Value.absent(),
    DateTime? updatedAt,
    bool? isDirty,
    bool? isDeleted,
  }) => SchoolPersonnelRow(
    id: id ?? this.id,
    serverId: serverId.present ? serverId.value : this.serverId,
    schoolId: schoolId ?? this.schoolId,
    fullName: fullName ?? this.fullName,
    positionTitle: positionTitle.present
        ? positionTitle.value
        : this.positionTitle,
    personnelType: personnelType ?? this.personnelType,
    email: email.present ? email.value : this.email,
    mobileNo: mobileNo.present ? mobileNo.value : this.mobileNo,
    updatedAt: updatedAt ?? this.updatedAt,
    isDirty: isDirty ?? this.isDirty,
    isDeleted: isDeleted ?? this.isDeleted,
  );
  SchoolPersonnelRow copyWithCompanion(SchoolPersonnelTableCompanion data) {
    return SchoolPersonnelRow(
      id: data.id.present ? data.id.value : this.id,
      serverId: data.serverId.present ? data.serverId.value : this.serverId,
      schoolId: data.schoolId.present ? data.schoolId.value : this.schoolId,
      fullName: data.fullName.present ? data.fullName.value : this.fullName,
      positionTitle: data.positionTitle.present
          ? data.positionTitle.value
          : this.positionTitle,
      personnelType: data.personnelType.present
          ? data.personnelType.value
          : this.personnelType,
      email: data.email.present ? data.email.value : this.email,
      mobileNo: data.mobileNo.present ? data.mobileNo.value : this.mobileNo,
      updatedAt: data.updatedAt.present ? data.updatedAt.value : this.updatedAt,
      isDirty: data.isDirty.present ? data.isDirty.value : this.isDirty,
      isDeleted: data.isDeleted.present ? data.isDeleted.value : this.isDeleted,
    );
  }

  @override
  String toString() {
    return (StringBuffer('SchoolPersonnelRow(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('schoolId: $schoolId, ')
          ..write('fullName: $fullName, ')
          ..write('positionTitle: $positionTitle, ')
          ..write('personnelType: $personnelType, ')
          ..write('email: $email, ')
          ..write('mobileNo: $mobileNo, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    serverId,
    schoolId,
    fullName,
    positionTitle,
    personnelType,
    email,
    mobileNo,
    updatedAt,
    isDirty,
    isDeleted,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is SchoolPersonnelRow &&
          other.id == this.id &&
          other.serverId == this.serverId &&
          other.schoolId == this.schoolId &&
          other.fullName == this.fullName &&
          other.positionTitle == this.positionTitle &&
          other.personnelType == this.personnelType &&
          other.email == this.email &&
          other.mobileNo == this.mobileNo &&
          other.updatedAt == this.updatedAt &&
          other.isDirty == this.isDirty &&
          other.isDeleted == this.isDeleted);
}

class SchoolPersonnelTableCompanion
    extends UpdateCompanion<SchoolPersonnelRow> {
  final Value<int> id;
  final Value<int?> serverId;
  final Value<String> schoolId;
  final Value<String> fullName;
  final Value<String?> positionTitle;
  final Value<String> personnelType;
  final Value<String?> email;
  final Value<String?> mobileNo;
  final Value<DateTime> updatedAt;
  final Value<bool> isDirty;
  final Value<bool> isDeleted;
  const SchoolPersonnelTableCompanion({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    this.schoolId = const Value.absent(),
    this.fullName = const Value.absent(),
    this.positionTitle = const Value.absent(),
    this.personnelType = const Value.absent(),
    this.email = const Value.absent(),
    this.mobileNo = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  });
  SchoolPersonnelTableCompanion.insert({
    this.id = const Value.absent(),
    this.serverId = const Value.absent(),
    required String schoolId,
    required String fullName,
    this.positionTitle = const Value.absent(),
    this.personnelType = const Value.absent(),
    this.email = const Value.absent(),
    this.mobileNo = const Value.absent(),
    this.updatedAt = const Value.absent(),
    this.isDirty = const Value.absent(),
    this.isDeleted = const Value.absent(),
  }) : schoolId = Value(schoolId),
       fullName = Value(fullName);
  static Insertable<SchoolPersonnelRow> custom({
    Expression<int>? id,
    Expression<int>? serverId,
    Expression<String>? schoolId,
    Expression<String>? fullName,
    Expression<String>? positionTitle,
    Expression<String>? personnelType,
    Expression<String>? email,
    Expression<String>? mobileNo,
    Expression<DateTime>? updatedAt,
    Expression<bool>? isDirty,
    Expression<bool>? isDeleted,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (serverId != null) 'server_id': serverId,
      if (schoolId != null) 'school_id': schoolId,
      if (fullName != null) 'full_name': fullName,
      if (positionTitle != null) 'position_title': positionTitle,
      if (personnelType != null) 'personnel_type': personnelType,
      if (email != null) 'email': email,
      if (mobileNo != null) 'mobile_no': mobileNo,
      if (updatedAt != null) 'updated_at': updatedAt,
      if (isDirty != null) 'is_dirty': isDirty,
      if (isDeleted != null) 'is_deleted': isDeleted,
    });
  }

  SchoolPersonnelTableCompanion copyWith({
    Value<int>? id,
    Value<int?>? serverId,
    Value<String>? schoolId,
    Value<String>? fullName,
    Value<String?>? positionTitle,
    Value<String>? personnelType,
    Value<String?>? email,
    Value<String?>? mobileNo,
    Value<DateTime>? updatedAt,
    Value<bool>? isDirty,
    Value<bool>? isDeleted,
  }) {
    return SchoolPersonnelTableCompanion(
      id: id ?? this.id,
      serverId: serverId ?? this.serverId,
      schoolId: schoolId ?? this.schoolId,
      fullName: fullName ?? this.fullName,
      positionTitle: positionTitle ?? this.positionTitle,
      personnelType: personnelType ?? this.personnelType,
      email: email ?? this.email,
      mobileNo: mobileNo ?? this.mobileNo,
      updatedAt: updatedAt ?? this.updatedAt,
      isDirty: isDirty ?? this.isDirty,
      isDeleted: isDeleted ?? this.isDeleted,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (serverId.present) {
      map['server_id'] = Variable<int>(serverId.value);
    }
    if (schoolId.present) {
      map['school_id'] = Variable<String>(schoolId.value);
    }
    if (fullName.present) {
      map['full_name'] = Variable<String>(fullName.value);
    }
    if (positionTitle.present) {
      map['position_title'] = Variable<String>(positionTitle.value);
    }
    if (personnelType.present) {
      map['personnel_type'] = Variable<String>(personnelType.value);
    }
    if (email.present) {
      map['email'] = Variable<String>(email.value);
    }
    if (mobileNo.present) {
      map['mobile_no'] = Variable<String>(mobileNo.value);
    }
    if (updatedAt.present) {
      map['updated_at'] = Variable<DateTime>(updatedAt.value);
    }
    if (isDirty.present) {
      map['is_dirty'] = Variable<bool>(isDirty.value);
    }
    if (isDeleted.present) {
      map['is_deleted'] = Variable<bool>(isDeleted.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('SchoolPersonnelTableCompanion(')
          ..write('id: $id, ')
          ..write('serverId: $serverId, ')
          ..write('schoolId: $schoolId, ')
          ..write('fullName: $fullName, ')
          ..write('positionTitle: $positionTitle, ')
          ..write('personnelType: $personnelType, ')
          ..write('email: $email, ')
          ..write('mobileNo: $mobileNo, ')
          ..write('updatedAt: $updatedAt, ')
          ..write('isDirty: $isDirty, ')
          ..write('isDeleted: $isDeleted')
          ..write(')'))
        .toString();
  }
}

class $SyncOutboxTableTable extends SyncOutboxTable
    with TableInfo<$SyncOutboxTableTable, SyncOutboxRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $SyncOutboxTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _idMeta = const VerificationMeta('id');
  @override
  late final GeneratedColumn<int> id = GeneratedColumn<int>(
    'id',
    aliasedName,
    false,
    hasAutoIncrement: true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'PRIMARY KEY AUTOINCREMENT',
    ),
  );
  static const VerificationMeta _operationMeta = const VerificationMeta(
    'operation',
  );
  @override
  late final GeneratedColumn<String> operation = GeneratedColumn<String>(
    'operation',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _entityMeta = const VerificationMeta('entity');
  @override
  late final GeneratedColumn<String> entity = GeneratedColumn<String>(
    'entity',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _localIdMeta = const VerificationMeta(
    'localId',
  );
  @override
  late final GeneratedColumn<int> localId = GeneratedColumn<int>(
    'local_id',
    aliasedName,
    true,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _payloadMeta = const VerificationMeta(
    'payload',
  );
  @override
  late final GeneratedColumn<String> payload = GeneratedColumn<String>(
    'payload',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _createdAtMeta = const VerificationMeta(
    'createdAt',
  );
  @override
  late final GeneratedColumn<DateTime> createdAt = GeneratedColumn<DateTime>(
    'created_at',
    aliasedName,
    false,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
    defaultValue: currentDateAndTime,
  );
  static const VerificationMeta _attemptsMeta = const VerificationMeta(
    'attempts',
  );
  @override
  late final GeneratedColumn<int> attempts = GeneratedColumn<int>(
    'attempts',
    aliasedName,
    false,
    type: DriftSqlType.int,
    requiredDuringInsert: false,
    defaultValue: const Constant(0),
  );
  static const VerificationMeta _failedMeta = const VerificationMeta('failed');
  @override
  late final GeneratedColumn<bool> failed = GeneratedColumn<bool>(
    'failed',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("failed" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  static const VerificationMeta _conflictMeta = const VerificationMeta(
    'conflict',
  );
  @override
  late final GeneratedColumn<bool> conflict = GeneratedColumn<bool>(
    'conflict',
    aliasedName,
    false,
    type: DriftSqlType.bool,
    requiredDuringInsert: false,
    defaultConstraints: GeneratedColumn.constraintIsAlways(
      'CHECK ("conflict" IN (0, 1))',
    ),
    defaultValue: const Constant(false),
  );
  static const VerificationMeta _errorMessageMeta = const VerificationMeta(
    'errorMessage',
  );
  @override
  late final GeneratedColumn<String> errorMessage = GeneratedColumn<String>(
    'error_message',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  @override
  List<GeneratedColumn> get $columns => [
    id,
    operation,
    entity,
    localId,
    payload,
    createdAt,
    attempts,
    failed,
    conflict,
    errorMessage,
  ];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'sync_outbox_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<SyncOutboxRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('id')) {
      context.handle(_idMeta, id.isAcceptableOrUnknown(data['id']!, _idMeta));
    }
    if (data.containsKey('operation')) {
      context.handle(
        _operationMeta,
        operation.isAcceptableOrUnknown(data['operation']!, _operationMeta),
      );
    } else if (isInserting) {
      context.missing(_operationMeta);
    }
    if (data.containsKey('entity')) {
      context.handle(
        _entityMeta,
        entity.isAcceptableOrUnknown(data['entity']!, _entityMeta),
      );
    } else if (isInserting) {
      context.missing(_entityMeta);
    }
    if (data.containsKey('local_id')) {
      context.handle(
        _localIdMeta,
        localId.isAcceptableOrUnknown(data['local_id']!, _localIdMeta),
      );
    }
    if (data.containsKey('payload')) {
      context.handle(
        _payloadMeta,
        payload.isAcceptableOrUnknown(data['payload']!, _payloadMeta),
      );
    } else if (isInserting) {
      context.missing(_payloadMeta);
    }
    if (data.containsKey('created_at')) {
      context.handle(
        _createdAtMeta,
        createdAt.isAcceptableOrUnknown(data['created_at']!, _createdAtMeta),
      );
    }
    if (data.containsKey('attempts')) {
      context.handle(
        _attemptsMeta,
        attempts.isAcceptableOrUnknown(data['attempts']!, _attemptsMeta),
      );
    }
    if (data.containsKey('failed')) {
      context.handle(
        _failedMeta,
        failed.isAcceptableOrUnknown(data['failed']!, _failedMeta),
      );
    }
    if (data.containsKey('conflict')) {
      context.handle(
        _conflictMeta,
        conflict.isAcceptableOrUnknown(data['conflict']!, _conflictMeta),
      );
    }
    if (data.containsKey('error_message')) {
      context.handle(
        _errorMessageMeta,
        errorMessage.isAcceptableOrUnknown(
          data['error_message']!,
          _errorMessageMeta,
        ),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {id};
  @override
  SyncOutboxRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return SyncOutboxRow(
      id: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}id'],
      )!,
      operation: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}operation'],
      )!,
      entity: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}entity'],
      )!,
      localId: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}local_id'],
      ),
      payload: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}payload'],
      )!,
      createdAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}created_at'],
      )!,
      attempts: attachedDatabase.typeMapping.read(
        DriftSqlType.int,
        data['${effectivePrefix}attempts'],
      )!,
      failed: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}failed'],
      )!,
      conflict: attachedDatabase.typeMapping.read(
        DriftSqlType.bool,
        data['${effectivePrefix}conflict'],
      )!,
      errorMessage: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}error_message'],
      ),
    );
  }

  @override
  $SyncOutboxTableTable createAlias(String alias) {
    return $SyncOutboxTableTable(attachedDatabase, alias);
  }
}

class SyncOutboxRow extends DataClass implements Insertable<SyncOutboxRow> {
  final int id;
  final String operation;
  final String entity;
  final int? localId;
  final String payload;
  final DateTime createdAt;
  final int attempts;
  final bool failed;
  final bool conflict;
  final String? errorMessage;
  const SyncOutboxRow({
    required this.id,
    required this.operation,
    required this.entity,
    this.localId,
    required this.payload,
    required this.createdAt,
    required this.attempts,
    required this.failed,
    required this.conflict,
    this.errorMessage,
  });
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['id'] = Variable<int>(id);
    map['operation'] = Variable<String>(operation);
    map['entity'] = Variable<String>(entity);
    if (!nullToAbsent || localId != null) {
      map['local_id'] = Variable<int>(localId);
    }
    map['payload'] = Variable<String>(payload);
    map['created_at'] = Variable<DateTime>(createdAt);
    map['attempts'] = Variable<int>(attempts);
    map['failed'] = Variable<bool>(failed);
    map['conflict'] = Variable<bool>(conflict);
    if (!nullToAbsent || errorMessage != null) {
      map['error_message'] = Variable<String>(errorMessage);
    }
    return map;
  }

  SyncOutboxTableCompanion toCompanion(bool nullToAbsent) {
    return SyncOutboxTableCompanion(
      id: Value(id),
      operation: Value(operation),
      entity: Value(entity),
      localId: localId == null && nullToAbsent
          ? const Value.absent()
          : Value(localId),
      payload: Value(payload),
      createdAt: Value(createdAt),
      attempts: Value(attempts),
      failed: Value(failed),
      conflict: Value(conflict),
      errorMessage: errorMessage == null && nullToAbsent
          ? const Value.absent()
          : Value(errorMessage),
    );
  }

  factory SyncOutboxRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return SyncOutboxRow(
      id: serializer.fromJson<int>(json['id']),
      operation: serializer.fromJson<String>(json['operation']),
      entity: serializer.fromJson<String>(json['entity']),
      localId: serializer.fromJson<int?>(json['localId']),
      payload: serializer.fromJson<String>(json['payload']),
      createdAt: serializer.fromJson<DateTime>(json['createdAt']),
      attempts: serializer.fromJson<int>(json['attempts']),
      failed: serializer.fromJson<bool>(json['failed']),
      conflict: serializer.fromJson<bool>(json['conflict']),
      errorMessage: serializer.fromJson<String?>(json['errorMessage']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'id': serializer.toJson<int>(id),
      'operation': serializer.toJson<String>(operation),
      'entity': serializer.toJson<String>(entity),
      'localId': serializer.toJson<int?>(localId),
      'payload': serializer.toJson<String>(payload),
      'createdAt': serializer.toJson<DateTime>(createdAt),
      'attempts': serializer.toJson<int>(attempts),
      'failed': serializer.toJson<bool>(failed),
      'conflict': serializer.toJson<bool>(conflict),
      'errorMessage': serializer.toJson<String?>(errorMessage),
    };
  }

  SyncOutboxRow copyWith({
    int? id,
    String? operation,
    String? entity,
    Value<int?> localId = const Value.absent(),
    String? payload,
    DateTime? createdAt,
    int? attempts,
    bool? failed,
    bool? conflict,
    Value<String?> errorMessage = const Value.absent(),
  }) => SyncOutboxRow(
    id: id ?? this.id,
    operation: operation ?? this.operation,
    entity: entity ?? this.entity,
    localId: localId.present ? localId.value : this.localId,
    payload: payload ?? this.payload,
    createdAt: createdAt ?? this.createdAt,
    attempts: attempts ?? this.attempts,
    failed: failed ?? this.failed,
    conflict: conflict ?? this.conflict,
    errorMessage: errorMessage.present ? errorMessage.value : this.errorMessage,
  );
  SyncOutboxRow copyWithCompanion(SyncOutboxTableCompanion data) {
    return SyncOutboxRow(
      id: data.id.present ? data.id.value : this.id,
      operation: data.operation.present ? data.operation.value : this.operation,
      entity: data.entity.present ? data.entity.value : this.entity,
      localId: data.localId.present ? data.localId.value : this.localId,
      payload: data.payload.present ? data.payload.value : this.payload,
      createdAt: data.createdAt.present ? data.createdAt.value : this.createdAt,
      attempts: data.attempts.present ? data.attempts.value : this.attempts,
      failed: data.failed.present ? data.failed.value : this.failed,
      conflict: data.conflict.present ? data.conflict.value : this.conflict,
      errorMessage: data.errorMessage.present
          ? data.errorMessage.value
          : this.errorMessage,
    );
  }

  @override
  String toString() {
    return (StringBuffer('SyncOutboxRow(')
          ..write('id: $id, ')
          ..write('operation: $operation, ')
          ..write('entity: $entity, ')
          ..write('localId: $localId, ')
          ..write('payload: $payload, ')
          ..write('createdAt: $createdAt, ')
          ..write('attempts: $attempts, ')
          ..write('failed: $failed, ')
          ..write('conflict: $conflict, ')
          ..write('errorMessage: $errorMessage')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(
    id,
    operation,
    entity,
    localId,
    payload,
    createdAt,
    attempts,
    failed,
    conflict,
    errorMessage,
  );
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is SyncOutboxRow &&
          other.id == this.id &&
          other.operation == this.operation &&
          other.entity == this.entity &&
          other.localId == this.localId &&
          other.payload == this.payload &&
          other.createdAt == this.createdAt &&
          other.attempts == this.attempts &&
          other.failed == this.failed &&
          other.conflict == this.conflict &&
          other.errorMessage == this.errorMessage);
}

class SyncOutboxTableCompanion extends UpdateCompanion<SyncOutboxRow> {
  final Value<int> id;
  final Value<String> operation;
  final Value<String> entity;
  final Value<int?> localId;
  final Value<String> payload;
  final Value<DateTime> createdAt;
  final Value<int> attempts;
  final Value<bool> failed;
  final Value<bool> conflict;
  final Value<String?> errorMessage;
  const SyncOutboxTableCompanion({
    this.id = const Value.absent(),
    this.operation = const Value.absent(),
    this.entity = const Value.absent(),
    this.localId = const Value.absent(),
    this.payload = const Value.absent(),
    this.createdAt = const Value.absent(),
    this.attempts = const Value.absent(),
    this.failed = const Value.absent(),
    this.conflict = const Value.absent(),
    this.errorMessage = const Value.absent(),
  });
  SyncOutboxTableCompanion.insert({
    this.id = const Value.absent(),
    required String operation,
    required String entity,
    this.localId = const Value.absent(),
    required String payload,
    this.createdAt = const Value.absent(),
    this.attempts = const Value.absent(),
    this.failed = const Value.absent(),
    this.conflict = const Value.absent(),
    this.errorMessage = const Value.absent(),
  }) : operation = Value(operation),
       entity = Value(entity),
       payload = Value(payload);
  static Insertable<SyncOutboxRow> custom({
    Expression<int>? id,
    Expression<String>? operation,
    Expression<String>? entity,
    Expression<int>? localId,
    Expression<String>? payload,
    Expression<DateTime>? createdAt,
    Expression<int>? attempts,
    Expression<bool>? failed,
    Expression<bool>? conflict,
    Expression<String>? errorMessage,
  }) {
    return RawValuesInsertable({
      if (id != null) 'id': id,
      if (operation != null) 'operation': operation,
      if (entity != null) 'entity': entity,
      if (localId != null) 'local_id': localId,
      if (payload != null) 'payload': payload,
      if (createdAt != null) 'created_at': createdAt,
      if (attempts != null) 'attempts': attempts,
      if (failed != null) 'failed': failed,
      if (conflict != null) 'conflict': conflict,
      if (errorMessage != null) 'error_message': errorMessage,
    });
  }

  SyncOutboxTableCompanion copyWith({
    Value<int>? id,
    Value<String>? operation,
    Value<String>? entity,
    Value<int?>? localId,
    Value<String>? payload,
    Value<DateTime>? createdAt,
    Value<int>? attempts,
    Value<bool>? failed,
    Value<bool>? conflict,
    Value<String?>? errorMessage,
  }) {
    return SyncOutboxTableCompanion(
      id: id ?? this.id,
      operation: operation ?? this.operation,
      entity: entity ?? this.entity,
      localId: localId ?? this.localId,
      payload: payload ?? this.payload,
      createdAt: createdAt ?? this.createdAt,
      attempts: attempts ?? this.attempts,
      failed: failed ?? this.failed,
      conflict: conflict ?? this.conflict,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (id.present) {
      map['id'] = Variable<int>(id.value);
    }
    if (operation.present) {
      map['operation'] = Variable<String>(operation.value);
    }
    if (entity.present) {
      map['entity'] = Variable<String>(entity.value);
    }
    if (localId.present) {
      map['local_id'] = Variable<int>(localId.value);
    }
    if (payload.present) {
      map['payload'] = Variable<String>(payload.value);
    }
    if (createdAt.present) {
      map['created_at'] = Variable<DateTime>(createdAt.value);
    }
    if (attempts.present) {
      map['attempts'] = Variable<int>(attempts.value);
    }
    if (failed.present) {
      map['failed'] = Variable<bool>(failed.value);
    }
    if (conflict.present) {
      map['conflict'] = Variable<bool>(conflict.value);
    }
    if (errorMessage.present) {
      map['error_message'] = Variable<String>(errorMessage.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('SyncOutboxTableCompanion(')
          ..write('id: $id, ')
          ..write('operation: $operation, ')
          ..write('entity: $entity, ')
          ..write('localId: $localId, ')
          ..write('payload: $payload, ')
          ..write('createdAt: $createdAt, ')
          ..write('attempts: $attempts, ')
          ..write('failed: $failed, ')
          ..write('conflict: $conflict, ')
          ..write('errorMessage: $errorMessage')
          ..write(')'))
        .toString();
  }
}

class $SyncMetadataTableTable extends SyncMetadataTable
    with TableInfo<$SyncMetadataTableTable, SyncMetadataRow> {
  @override
  final GeneratedDatabase attachedDatabase;
  final String? _alias;
  $SyncMetadataTableTable(this.attachedDatabase, [this._alias]);
  static const VerificationMeta _tableKeyMeta = const VerificationMeta(
    'tableKey',
  );
  @override
  late final GeneratedColumn<String> tableKey = GeneratedColumn<String>(
    'table_key',
    aliasedName,
    false,
    type: DriftSqlType.string,
    requiredDuringInsert: true,
  );
  static const VerificationMeta _lastSyncedAtMeta = const VerificationMeta(
    'lastSyncedAt',
  );
  @override
  late final GeneratedColumn<DateTime> lastSyncedAt = GeneratedColumn<DateTime>(
    'last_synced_at',
    aliasedName,
    true,
    type: DriftSqlType.dateTime,
    requiredDuringInsert: false,
  );
  static const VerificationMeta _etagMeta = const VerificationMeta('etag');
  @override
  late final GeneratedColumn<String> etag = GeneratedColumn<String>(
    'etag',
    aliasedName,
    true,
    type: DriftSqlType.string,
    requiredDuringInsert: false,
  );
  @override
  List<GeneratedColumn> get $columns => [tableKey, lastSyncedAt, etag];
  @override
  String get aliasedName => _alias ?? actualTableName;
  @override
  String get actualTableName => $name;
  static const String $name = 'sync_metadata_table';
  @override
  VerificationContext validateIntegrity(
    Insertable<SyncMetadataRow> instance, {
    bool isInserting = false,
  }) {
    final context = VerificationContext();
    final data = instance.toColumns(true);
    if (data.containsKey('table_key')) {
      context.handle(
        _tableKeyMeta,
        tableKey.isAcceptableOrUnknown(data['table_key']!, _tableKeyMeta),
      );
    } else if (isInserting) {
      context.missing(_tableKeyMeta);
    }
    if (data.containsKey('last_synced_at')) {
      context.handle(
        _lastSyncedAtMeta,
        lastSyncedAt.isAcceptableOrUnknown(
          data['last_synced_at']!,
          _lastSyncedAtMeta,
        ),
      );
    }
    if (data.containsKey('etag')) {
      context.handle(
        _etagMeta,
        etag.isAcceptableOrUnknown(data['etag']!, _etagMeta),
      );
    }
    return context;
  }

  @override
  Set<GeneratedColumn> get $primaryKey => {tableKey};
  @override
  SyncMetadataRow map(Map<String, dynamic> data, {String? tablePrefix}) {
    final effectivePrefix = tablePrefix != null ? '$tablePrefix.' : '';
    return SyncMetadataRow(
      tableKey: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}table_key'],
      )!,
      lastSyncedAt: attachedDatabase.typeMapping.read(
        DriftSqlType.dateTime,
        data['${effectivePrefix}last_synced_at'],
      ),
      etag: attachedDatabase.typeMapping.read(
        DriftSqlType.string,
        data['${effectivePrefix}etag'],
      ),
    );
  }

  @override
  $SyncMetadataTableTable createAlias(String alias) {
    return $SyncMetadataTableTable(attachedDatabase, alias);
  }
}

class SyncMetadataRow extends DataClass implements Insertable<SyncMetadataRow> {
  final String tableKey;
  final DateTime? lastSyncedAt;
  final String? etag;
  const SyncMetadataRow({required this.tableKey, this.lastSyncedAt, this.etag});
  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    map['table_key'] = Variable<String>(tableKey);
    if (!nullToAbsent || lastSyncedAt != null) {
      map['last_synced_at'] = Variable<DateTime>(lastSyncedAt);
    }
    if (!nullToAbsent || etag != null) {
      map['etag'] = Variable<String>(etag);
    }
    return map;
  }

  SyncMetadataTableCompanion toCompanion(bool nullToAbsent) {
    return SyncMetadataTableCompanion(
      tableKey: Value(tableKey),
      lastSyncedAt: lastSyncedAt == null && nullToAbsent
          ? const Value.absent()
          : Value(lastSyncedAt),
      etag: etag == null && nullToAbsent ? const Value.absent() : Value(etag),
    );
  }

  factory SyncMetadataRow.fromJson(
    Map<String, dynamic> json, {
    ValueSerializer? serializer,
  }) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return SyncMetadataRow(
      tableKey: serializer.fromJson<String>(json['tableKey']),
      lastSyncedAt: serializer.fromJson<DateTime?>(json['lastSyncedAt']),
      etag: serializer.fromJson<String?>(json['etag']),
    );
  }
  @override
  Map<String, dynamic> toJson({ValueSerializer? serializer}) {
    serializer ??= driftRuntimeOptions.defaultSerializer;
    return <String, dynamic>{
      'tableKey': serializer.toJson<String>(tableKey),
      'lastSyncedAt': serializer.toJson<DateTime?>(lastSyncedAt),
      'etag': serializer.toJson<String?>(etag),
    };
  }

  SyncMetadataRow copyWith({
    String? tableKey,
    Value<DateTime?> lastSyncedAt = const Value.absent(),
    Value<String?> etag = const Value.absent(),
  }) => SyncMetadataRow(
    tableKey: tableKey ?? this.tableKey,
    lastSyncedAt: lastSyncedAt.present ? lastSyncedAt.value : this.lastSyncedAt,
    etag: etag.present ? etag.value : this.etag,
  );
  SyncMetadataRow copyWithCompanion(SyncMetadataTableCompanion data) {
    return SyncMetadataRow(
      tableKey: data.tableKey.present ? data.tableKey.value : this.tableKey,
      lastSyncedAt: data.lastSyncedAt.present
          ? data.lastSyncedAt.value
          : this.lastSyncedAt,
      etag: data.etag.present ? data.etag.value : this.etag,
    );
  }

  @override
  String toString() {
    return (StringBuffer('SyncMetadataRow(')
          ..write('tableKey: $tableKey, ')
          ..write('lastSyncedAt: $lastSyncedAt, ')
          ..write('etag: $etag')
          ..write(')'))
        .toString();
  }

  @override
  int get hashCode => Object.hash(tableKey, lastSyncedAt, etag);
  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      (other is SyncMetadataRow &&
          other.tableKey == this.tableKey &&
          other.lastSyncedAt == this.lastSyncedAt &&
          other.etag == this.etag);
}

class SyncMetadataTableCompanion extends UpdateCompanion<SyncMetadataRow> {
  final Value<String> tableKey;
  final Value<DateTime?> lastSyncedAt;
  final Value<String?> etag;
  final Value<int> rowid;
  const SyncMetadataTableCompanion({
    this.tableKey = const Value.absent(),
    this.lastSyncedAt = const Value.absent(),
    this.etag = const Value.absent(),
    this.rowid = const Value.absent(),
  });
  SyncMetadataTableCompanion.insert({
    required String tableKey,
    this.lastSyncedAt = const Value.absent(),
    this.etag = const Value.absent(),
    this.rowid = const Value.absent(),
  }) : tableKey = Value(tableKey);
  static Insertable<SyncMetadataRow> custom({
    Expression<String>? tableKey,
    Expression<DateTime>? lastSyncedAt,
    Expression<String>? etag,
    Expression<int>? rowid,
  }) {
    return RawValuesInsertable({
      if (tableKey != null) 'table_key': tableKey,
      if (lastSyncedAt != null) 'last_synced_at': lastSyncedAt,
      if (etag != null) 'etag': etag,
      if (rowid != null) 'rowid': rowid,
    });
  }

  SyncMetadataTableCompanion copyWith({
    Value<String>? tableKey,
    Value<DateTime?>? lastSyncedAt,
    Value<String?>? etag,
    Value<int>? rowid,
  }) {
    return SyncMetadataTableCompanion(
      tableKey: tableKey ?? this.tableKey,
      lastSyncedAt: lastSyncedAt ?? this.lastSyncedAt,
      etag: etag ?? this.etag,
      rowid: rowid ?? this.rowid,
    );
  }

  @override
  Map<String, Expression> toColumns(bool nullToAbsent) {
    final map = <String, Expression>{};
    if (tableKey.present) {
      map['table_key'] = Variable<String>(tableKey.value);
    }
    if (lastSyncedAt.present) {
      map['last_synced_at'] = Variable<DateTime>(lastSyncedAt.value);
    }
    if (etag.present) {
      map['etag'] = Variable<String>(etag.value);
    }
    if (rowid.present) {
      map['rowid'] = Variable<int>(rowid.value);
    }
    return map;
  }

  @override
  String toString() {
    return (StringBuffer('SyncMetadataTableCompanion(')
          ..write('tableKey: $tableKey, ')
          ..write('lastSyncedAt: $lastSyncedAt, ')
          ..write('etag: $etag, ')
          ..write('rowid: $rowid')
          ..write(')'))
        .toString();
  }
}

abstract class _$AppDatabase extends GeneratedDatabase {
  _$AppDatabase(QueryExecutor e) : super(e);
  $AppDatabaseManager get managers => $AppDatabaseManager(this);
  late final $UserProfileTableTable userProfileTable = $UserProfileTableTable(
    this,
  );
  late final $MemoTableTable memoTable = $MemoTableTable(this);
  late final $AccomplishmentTableTable accomplishmentTable =
      $AccomplishmentTableTable(this);
  late final $SchoolTableTable schoolTable = $SchoolTableTable(this);
  late final $SchoolPersonnelTableTable schoolPersonnelTable =
      $SchoolPersonnelTableTable(this);
  late final $SyncOutboxTableTable syncOutboxTable = $SyncOutboxTableTable(
    this,
  );
  late final $SyncMetadataTableTable syncMetadataTable =
      $SyncMetadataTableTable(this);
  @override
  Iterable<TableInfo<Table, Object?>> get allTables =>
      allSchemaEntities.whereType<TableInfo<Table, Object?>>();
  @override
  List<DatabaseSchemaEntity> get allSchemaEntities => [
    userProfileTable,
    memoTable,
    accomplishmentTable,
    schoolTable,
    schoolPersonnelTable,
    syncOutboxTable,
    syncMetadataTable,
  ];
}

typedef $$UserProfileTableTableCreateCompanionBuilder =
    UserProfileTableCompanion Function({
      Value<int> id,
      required String username,
      Value<String> position,
      Value<String> fname,
      Value<String> lname,
      Value<String?> email,
      Value<String?> avatar,
      Value<String> section,
      Value<String> secGroup,
      Value<String> loginSource,
      Value<DateTime> updatedAt,
    });
typedef $$UserProfileTableTableUpdateCompanionBuilder =
    UserProfileTableCompanion Function({
      Value<int> id,
      Value<String> username,
      Value<String> position,
      Value<String> fname,
      Value<String> lname,
      Value<String?> email,
      Value<String?> avatar,
      Value<String> section,
      Value<String> secGroup,
      Value<String> loginSource,
      Value<DateTime> updatedAt,
    });

class $$UserProfileTableTableFilterComposer
    extends Composer<_$AppDatabase, $UserProfileTableTable> {
  $$UserProfileTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get username => $composableBuilder(
    column: $table.username,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get position => $composableBuilder(
    column: $table.position,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get fname => $composableBuilder(
    column: $table.fname,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get lname => $composableBuilder(
    column: $table.lname,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get email => $composableBuilder(
    column: $table.email,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get avatar => $composableBuilder(
    column: $table.avatar,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get section => $composableBuilder(
    column: $table.section,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get secGroup => $composableBuilder(
    column: $table.secGroup,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get loginSource => $composableBuilder(
    column: $table.loginSource,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnFilters(column),
  );
}

class $$UserProfileTableTableOrderingComposer
    extends Composer<_$AppDatabase, $UserProfileTableTable> {
  $$UserProfileTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get username => $composableBuilder(
    column: $table.username,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get position => $composableBuilder(
    column: $table.position,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get fname => $composableBuilder(
    column: $table.fname,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get lname => $composableBuilder(
    column: $table.lname,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get email => $composableBuilder(
    column: $table.email,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get avatar => $composableBuilder(
    column: $table.avatar,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get section => $composableBuilder(
    column: $table.section,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get secGroup => $composableBuilder(
    column: $table.secGroup,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get loginSource => $composableBuilder(
    column: $table.loginSource,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$UserProfileTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $UserProfileTableTable> {
  $$UserProfileTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<String> get username =>
      $composableBuilder(column: $table.username, builder: (column) => column);

  GeneratedColumn<String> get position =>
      $composableBuilder(column: $table.position, builder: (column) => column);

  GeneratedColumn<String> get fname =>
      $composableBuilder(column: $table.fname, builder: (column) => column);

  GeneratedColumn<String> get lname =>
      $composableBuilder(column: $table.lname, builder: (column) => column);

  GeneratedColumn<String> get email =>
      $composableBuilder(column: $table.email, builder: (column) => column);

  GeneratedColumn<String> get avatar =>
      $composableBuilder(column: $table.avatar, builder: (column) => column);

  GeneratedColumn<String> get section =>
      $composableBuilder(column: $table.section, builder: (column) => column);

  GeneratedColumn<String> get secGroup =>
      $composableBuilder(column: $table.secGroup, builder: (column) => column);

  GeneratedColumn<String> get loginSource => $composableBuilder(
    column: $table.loginSource,
    builder: (column) => column,
  );

  GeneratedColumn<DateTime> get updatedAt =>
      $composableBuilder(column: $table.updatedAt, builder: (column) => column);
}

class $$UserProfileTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $UserProfileTableTable,
          UserProfileRow,
          $$UserProfileTableTableFilterComposer,
          $$UserProfileTableTableOrderingComposer,
          $$UserProfileTableTableAnnotationComposer,
          $$UserProfileTableTableCreateCompanionBuilder,
          $$UserProfileTableTableUpdateCompanionBuilder,
          (
            UserProfileRow,
            BaseReferences<
              _$AppDatabase,
              $UserProfileTableTable,
              UserProfileRow
            >,
          ),
          UserProfileRow,
          PrefetchHooks Function()
        > {
  $$UserProfileTableTableTableManager(
    _$AppDatabase db,
    $UserProfileTableTable table,
  ) : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$UserProfileTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$UserProfileTableTableOrderingComposer($db: db, $table: table),
          createComputedFieldComposer: () =>
              $$UserProfileTableTableAnnotationComposer($db: db, $table: table),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<String> username = const Value.absent(),
                Value<String> position = const Value.absent(),
                Value<String> fname = const Value.absent(),
                Value<String> lname = const Value.absent(),
                Value<String?> email = const Value.absent(),
                Value<String?> avatar = const Value.absent(),
                Value<String> section = const Value.absent(),
                Value<String> secGroup = const Value.absent(),
                Value<String> loginSource = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
              }) => UserProfileTableCompanion(
                id: id,
                username: username,
                position: position,
                fname: fname,
                lname: lname,
                email: email,
                avatar: avatar,
                section: section,
                secGroup: secGroup,
                loginSource: loginSource,
                updatedAt: updatedAt,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                required String username,
                Value<String> position = const Value.absent(),
                Value<String> fname = const Value.absent(),
                Value<String> lname = const Value.absent(),
                Value<String?> email = const Value.absent(),
                Value<String?> avatar = const Value.absent(),
                Value<String> section = const Value.absent(),
                Value<String> secGroup = const Value.absent(),
                Value<String> loginSource = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
              }) => UserProfileTableCompanion.insert(
                id: id,
                username: username,
                position: position,
                fname: fname,
                lname: lname,
                email: email,
                avatar: avatar,
                section: section,
                secGroup: secGroup,
                loginSource: loginSource,
                updatedAt: updatedAt,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$UserProfileTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $UserProfileTableTable,
      UserProfileRow,
      $$UserProfileTableTableFilterComposer,
      $$UserProfileTableTableOrderingComposer,
      $$UserProfileTableTableAnnotationComposer,
      $$UserProfileTableTableCreateCompanionBuilder,
      $$UserProfileTableTableUpdateCompanionBuilder,
      (
        UserProfileRow,
        BaseReferences<_$AppDatabase, $UserProfileTableTable, UserProfileRow>,
      ),
      UserProfileRow,
      PrefetchHooks Function()
    >;
typedef $$MemoTableTableCreateCompanionBuilder =
    MemoTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      required String title,
      Value<String> body,
      Value<String?> attachment,
      Value<DateTime?> createdAt,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });
typedef $$MemoTableTableUpdateCompanionBuilder =
    MemoTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      Value<String> title,
      Value<String> body,
      Value<String?> attachment,
      Value<DateTime?> createdAt,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });

class $$MemoTableTableFilterComposer
    extends Composer<_$AppDatabase, $MemoTableTable> {
  $$MemoTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get title => $composableBuilder(
    column: $table.title,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get body => $composableBuilder(
    column: $table.body,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get attachment => $composableBuilder(
    column: $table.attachment,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get createdAt => $composableBuilder(
    column: $table.createdAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnFilters(column),
  );
}

class $$MemoTableTableOrderingComposer
    extends Composer<_$AppDatabase, $MemoTableTable> {
  $$MemoTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get title => $composableBuilder(
    column: $table.title,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get body => $composableBuilder(
    column: $table.body,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get attachment => $composableBuilder(
    column: $table.attachment,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get createdAt => $composableBuilder(
    column: $table.createdAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$MemoTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $MemoTableTable> {
  $$MemoTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<int> get serverId =>
      $composableBuilder(column: $table.serverId, builder: (column) => column);

  GeneratedColumn<String> get title =>
      $composableBuilder(column: $table.title, builder: (column) => column);

  GeneratedColumn<String> get body =>
      $composableBuilder(column: $table.body, builder: (column) => column);

  GeneratedColumn<String> get attachment => $composableBuilder(
    column: $table.attachment,
    builder: (column) => column,
  );

  GeneratedColumn<DateTime> get createdAt =>
      $composableBuilder(column: $table.createdAt, builder: (column) => column);

  GeneratedColumn<DateTime> get updatedAt =>
      $composableBuilder(column: $table.updatedAt, builder: (column) => column);

  GeneratedColumn<bool> get isDirty =>
      $composableBuilder(column: $table.isDirty, builder: (column) => column);

  GeneratedColumn<bool> get isDeleted =>
      $composableBuilder(column: $table.isDeleted, builder: (column) => column);
}

class $$MemoTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $MemoTableTable,
          MemoRow,
          $$MemoTableTableFilterComposer,
          $$MemoTableTableOrderingComposer,
          $$MemoTableTableAnnotationComposer,
          $$MemoTableTableCreateCompanionBuilder,
          $$MemoTableTableUpdateCompanionBuilder,
          (MemoRow, BaseReferences<_$AppDatabase, $MemoTableTable, MemoRow>),
          MemoRow,
          PrefetchHooks Function()
        > {
  $$MemoTableTableTableManager(_$AppDatabase db, $MemoTableTable table)
    : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$MemoTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$MemoTableTableOrderingComposer($db: db, $table: table),
          createComputedFieldComposer: () =>
              $$MemoTableTableAnnotationComposer($db: db, $table: table),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                Value<String> title = const Value.absent(),
                Value<String> body = const Value.absent(),
                Value<String?> attachment = const Value.absent(),
                Value<DateTime?> createdAt = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => MemoTableCompanion(
                id: id,
                serverId: serverId,
                title: title,
                body: body,
                attachment: attachment,
                createdAt: createdAt,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                required String title,
                Value<String> body = const Value.absent(),
                Value<String?> attachment = const Value.absent(),
                Value<DateTime?> createdAt = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => MemoTableCompanion.insert(
                id: id,
                serverId: serverId,
                title: title,
                body: body,
                attachment: attachment,
                createdAt: createdAt,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$MemoTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $MemoTableTable,
      MemoRow,
      $$MemoTableTableFilterComposer,
      $$MemoTableTableOrderingComposer,
      $$MemoTableTableAnnotationComposer,
      $$MemoTableTableCreateCompanionBuilder,
      $$MemoTableTableUpdateCompanionBuilder,
      (MemoRow, BaseReferences<_$AppDatabase, $MemoTableTable, MemoRow>),
      MemoRow,
      PrefetchHooks Function()
    >;
typedef $$AccomplishmentTableTableCreateCompanionBuilder =
    AccomplishmentTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      required String title,
      Value<String> section,
      Value<String> status,
      Value<String> details,
      Value<DateTime?> date,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });
typedef $$AccomplishmentTableTableUpdateCompanionBuilder =
    AccomplishmentTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      Value<String> title,
      Value<String> section,
      Value<String> status,
      Value<String> details,
      Value<DateTime?> date,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });

class $$AccomplishmentTableTableFilterComposer
    extends Composer<_$AppDatabase, $AccomplishmentTableTable> {
  $$AccomplishmentTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get title => $composableBuilder(
    column: $table.title,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get section => $composableBuilder(
    column: $table.section,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get status => $composableBuilder(
    column: $table.status,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get details => $composableBuilder(
    column: $table.details,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get date => $composableBuilder(
    column: $table.date,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnFilters(column),
  );
}

class $$AccomplishmentTableTableOrderingComposer
    extends Composer<_$AppDatabase, $AccomplishmentTableTable> {
  $$AccomplishmentTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get title => $composableBuilder(
    column: $table.title,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get section => $composableBuilder(
    column: $table.section,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get status => $composableBuilder(
    column: $table.status,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get details => $composableBuilder(
    column: $table.details,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get date => $composableBuilder(
    column: $table.date,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$AccomplishmentTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $AccomplishmentTableTable> {
  $$AccomplishmentTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<int> get serverId =>
      $composableBuilder(column: $table.serverId, builder: (column) => column);

  GeneratedColumn<String> get title =>
      $composableBuilder(column: $table.title, builder: (column) => column);

  GeneratedColumn<String> get section =>
      $composableBuilder(column: $table.section, builder: (column) => column);

  GeneratedColumn<String> get status =>
      $composableBuilder(column: $table.status, builder: (column) => column);

  GeneratedColumn<String> get details =>
      $composableBuilder(column: $table.details, builder: (column) => column);

  GeneratedColumn<DateTime> get date =>
      $composableBuilder(column: $table.date, builder: (column) => column);

  GeneratedColumn<DateTime> get updatedAt =>
      $composableBuilder(column: $table.updatedAt, builder: (column) => column);

  GeneratedColumn<bool> get isDirty =>
      $composableBuilder(column: $table.isDirty, builder: (column) => column);

  GeneratedColumn<bool> get isDeleted =>
      $composableBuilder(column: $table.isDeleted, builder: (column) => column);
}

class $$AccomplishmentTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $AccomplishmentTableTable,
          AccomplishmentRow,
          $$AccomplishmentTableTableFilterComposer,
          $$AccomplishmentTableTableOrderingComposer,
          $$AccomplishmentTableTableAnnotationComposer,
          $$AccomplishmentTableTableCreateCompanionBuilder,
          $$AccomplishmentTableTableUpdateCompanionBuilder,
          (
            AccomplishmentRow,
            BaseReferences<
              _$AppDatabase,
              $AccomplishmentTableTable,
              AccomplishmentRow
            >,
          ),
          AccomplishmentRow,
          PrefetchHooks Function()
        > {
  $$AccomplishmentTableTableTableManager(
    _$AppDatabase db,
    $AccomplishmentTableTable table,
  ) : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$AccomplishmentTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$AccomplishmentTableTableOrderingComposer(
                $db: db,
                $table: table,
              ),
          createComputedFieldComposer: () =>
              $$AccomplishmentTableTableAnnotationComposer(
                $db: db,
                $table: table,
              ),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                Value<String> title = const Value.absent(),
                Value<String> section = const Value.absent(),
                Value<String> status = const Value.absent(),
                Value<String> details = const Value.absent(),
                Value<DateTime?> date = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => AccomplishmentTableCompanion(
                id: id,
                serverId: serverId,
                title: title,
                section: section,
                status: status,
                details: details,
                date: date,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                required String title,
                Value<String> section = const Value.absent(),
                Value<String> status = const Value.absent(),
                Value<String> details = const Value.absent(),
                Value<DateTime?> date = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => AccomplishmentTableCompanion.insert(
                id: id,
                serverId: serverId,
                title: title,
                section: section,
                status: status,
                details: details,
                date: date,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$AccomplishmentTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $AccomplishmentTableTable,
      AccomplishmentRow,
      $$AccomplishmentTableTableFilterComposer,
      $$AccomplishmentTableTableOrderingComposer,
      $$AccomplishmentTableTableAnnotationComposer,
      $$AccomplishmentTableTableCreateCompanionBuilder,
      $$AccomplishmentTableTableUpdateCompanionBuilder,
      (
        AccomplishmentRow,
        BaseReferences<
          _$AppDatabase,
          $AccomplishmentTableTable,
          AccomplishmentRow
        >,
      ),
      AccomplishmentRow,
      PrefetchHooks Function()
    >;
typedef $$SchoolTableTableCreateCompanionBuilder =
    SchoolTableCompanion Function({
      Value<int> id,
      required String schoolId,
      required String schoolName,
      Value<String> district,
      Value<String> division,
      Value<String?> schoolEmail,
      Value<String> schoolType,
      Value<DateTime> updatedAt,
    });
typedef $$SchoolTableTableUpdateCompanionBuilder =
    SchoolTableCompanion Function({
      Value<int> id,
      Value<String> schoolId,
      Value<String> schoolName,
      Value<String> district,
      Value<String> division,
      Value<String?> schoolEmail,
      Value<String> schoolType,
      Value<DateTime> updatedAt,
    });

class $$SchoolTableTableFilterComposer
    extends Composer<_$AppDatabase, $SchoolTableTable> {
  $$SchoolTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get schoolId => $composableBuilder(
    column: $table.schoolId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get schoolName => $composableBuilder(
    column: $table.schoolName,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get district => $composableBuilder(
    column: $table.district,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get division => $composableBuilder(
    column: $table.division,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get schoolEmail => $composableBuilder(
    column: $table.schoolEmail,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get schoolType => $composableBuilder(
    column: $table.schoolType,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnFilters(column),
  );
}

class $$SchoolTableTableOrderingComposer
    extends Composer<_$AppDatabase, $SchoolTableTable> {
  $$SchoolTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get schoolId => $composableBuilder(
    column: $table.schoolId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get schoolName => $composableBuilder(
    column: $table.schoolName,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get district => $composableBuilder(
    column: $table.district,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get division => $composableBuilder(
    column: $table.division,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get schoolEmail => $composableBuilder(
    column: $table.schoolEmail,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get schoolType => $composableBuilder(
    column: $table.schoolType,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$SchoolTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $SchoolTableTable> {
  $$SchoolTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<String> get schoolId =>
      $composableBuilder(column: $table.schoolId, builder: (column) => column);

  GeneratedColumn<String> get schoolName => $composableBuilder(
    column: $table.schoolName,
    builder: (column) => column,
  );

  GeneratedColumn<String> get district =>
      $composableBuilder(column: $table.district, builder: (column) => column);

  GeneratedColumn<String> get division =>
      $composableBuilder(column: $table.division, builder: (column) => column);

  GeneratedColumn<String> get schoolEmail => $composableBuilder(
    column: $table.schoolEmail,
    builder: (column) => column,
  );

  GeneratedColumn<String> get schoolType => $composableBuilder(
    column: $table.schoolType,
    builder: (column) => column,
  );

  GeneratedColumn<DateTime> get updatedAt =>
      $composableBuilder(column: $table.updatedAt, builder: (column) => column);
}

class $$SchoolTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $SchoolTableTable,
          SchoolRow,
          $$SchoolTableTableFilterComposer,
          $$SchoolTableTableOrderingComposer,
          $$SchoolTableTableAnnotationComposer,
          $$SchoolTableTableCreateCompanionBuilder,
          $$SchoolTableTableUpdateCompanionBuilder,
          (
            SchoolRow,
            BaseReferences<_$AppDatabase, $SchoolTableTable, SchoolRow>,
          ),
          SchoolRow,
          PrefetchHooks Function()
        > {
  $$SchoolTableTableTableManager(_$AppDatabase db, $SchoolTableTable table)
    : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$SchoolTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$SchoolTableTableOrderingComposer($db: db, $table: table),
          createComputedFieldComposer: () =>
              $$SchoolTableTableAnnotationComposer($db: db, $table: table),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<String> schoolId = const Value.absent(),
                Value<String> schoolName = const Value.absent(),
                Value<String> district = const Value.absent(),
                Value<String> division = const Value.absent(),
                Value<String?> schoolEmail = const Value.absent(),
                Value<String> schoolType = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
              }) => SchoolTableCompanion(
                id: id,
                schoolId: schoolId,
                schoolName: schoolName,
                district: district,
                division: division,
                schoolEmail: schoolEmail,
                schoolType: schoolType,
                updatedAt: updatedAt,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                required String schoolId,
                required String schoolName,
                Value<String> district = const Value.absent(),
                Value<String> division = const Value.absent(),
                Value<String?> schoolEmail = const Value.absent(),
                Value<String> schoolType = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
              }) => SchoolTableCompanion.insert(
                id: id,
                schoolId: schoolId,
                schoolName: schoolName,
                district: district,
                division: division,
                schoolEmail: schoolEmail,
                schoolType: schoolType,
                updatedAt: updatedAt,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$SchoolTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $SchoolTableTable,
      SchoolRow,
      $$SchoolTableTableFilterComposer,
      $$SchoolTableTableOrderingComposer,
      $$SchoolTableTableAnnotationComposer,
      $$SchoolTableTableCreateCompanionBuilder,
      $$SchoolTableTableUpdateCompanionBuilder,
      (SchoolRow, BaseReferences<_$AppDatabase, $SchoolTableTable, SchoolRow>),
      SchoolRow,
      PrefetchHooks Function()
    >;
typedef $$SchoolPersonnelTableTableCreateCompanionBuilder =
    SchoolPersonnelTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      required String schoolId,
      required String fullName,
      Value<String?> positionTitle,
      Value<String> personnelType,
      Value<String?> email,
      Value<String?> mobileNo,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });
typedef $$SchoolPersonnelTableTableUpdateCompanionBuilder =
    SchoolPersonnelTableCompanion Function({
      Value<int> id,
      Value<int?> serverId,
      Value<String> schoolId,
      Value<String> fullName,
      Value<String?> positionTitle,
      Value<String> personnelType,
      Value<String?> email,
      Value<String?> mobileNo,
      Value<DateTime> updatedAt,
      Value<bool> isDirty,
      Value<bool> isDeleted,
    });

class $$SchoolPersonnelTableTableFilterComposer
    extends Composer<_$AppDatabase, $SchoolPersonnelTableTable> {
  $$SchoolPersonnelTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get schoolId => $composableBuilder(
    column: $table.schoolId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get fullName => $composableBuilder(
    column: $table.fullName,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get positionTitle => $composableBuilder(
    column: $table.positionTitle,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get personnelType => $composableBuilder(
    column: $table.personnelType,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get email => $composableBuilder(
    column: $table.email,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get mobileNo => $composableBuilder(
    column: $table.mobileNo,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnFilters(column),
  );
}

class $$SchoolPersonnelTableTableOrderingComposer
    extends Composer<_$AppDatabase, $SchoolPersonnelTableTable> {
  $$SchoolPersonnelTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<int> get serverId => $composableBuilder(
    column: $table.serverId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get schoolId => $composableBuilder(
    column: $table.schoolId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get fullName => $composableBuilder(
    column: $table.fullName,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get positionTitle => $composableBuilder(
    column: $table.positionTitle,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get personnelType => $composableBuilder(
    column: $table.personnelType,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get email => $composableBuilder(
    column: $table.email,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get mobileNo => $composableBuilder(
    column: $table.mobileNo,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get updatedAt => $composableBuilder(
    column: $table.updatedAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDirty => $composableBuilder(
    column: $table.isDirty,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get isDeleted => $composableBuilder(
    column: $table.isDeleted,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$SchoolPersonnelTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $SchoolPersonnelTableTable> {
  $$SchoolPersonnelTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<int> get serverId =>
      $composableBuilder(column: $table.serverId, builder: (column) => column);

  GeneratedColumn<String> get schoolId =>
      $composableBuilder(column: $table.schoolId, builder: (column) => column);

  GeneratedColumn<String> get fullName =>
      $composableBuilder(column: $table.fullName, builder: (column) => column);

  GeneratedColumn<String> get positionTitle => $composableBuilder(
    column: $table.positionTitle,
    builder: (column) => column,
  );

  GeneratedColumn<String> get personnelType => $composableBuilder(
    column: $table.personnelType,
    builder: (column) => column,
  );

  GeneratedColumn<String> get email =>
      $composableBuilder(column: $table.email, builder: (column) => column);

  GeneratedColumn<String> get mobileNo =>
      $composableBuilder(column: $table.mobileNo, builder: (column) => column);

  GeneratedColumn<DateTime> get updatedAt =>
      $composableBuilder(column: $table.updatedAt, builder: (column) => column);

  GeneratedColumn<bool> get isDirty =>
      $composableBuilder(column: $table.isDirty, builder: (column) => column);

  GeneratedColumn<bool> get isDeleted =>
      $composableBuilder(column: $table.isDeleted, builder: (column) => column);
}

class $$SchoolPersonnelTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $SchoolPersonnelTableTable,
          SchoolPersonnelRow,
          $$SchoolPersonnelTableTableFilterComposer,
          $$SchoolPersonnelTableTableOrderingComposer,
          $$SchoolPersonnelTableTableAnnotationComposer,
          $$SchoolPersonnelTableTableCreateCompanionBuilder,
          $$SchoolPersonnelTableTableUpdateCompanionBuilder,
          (
            SchoolPersonnelRow,
            BaseReferences<
              _$AppDatabase,
              $SchoolPersonnelTableTable,
              SchoolPersonnelRow
            >,
          ),
          SchoolPersonnelRow,
          PrefetchHooks Function()
        > {
  $$SchoolPersonnelTableTableTableManager(
    _$AppDatabase db,
    $SchoolPersonnelTableTable table,
  ) : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$SchoolPersonnelTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$SchoolPersonnelTableTableOrderingComposer(
                $db: db,
                $table: table,
              ),
          createComputedFieldComposer: () =>
              $$SchoolPersonnelTableTableAnnotationComposer(
                $db: db,
                $table: table,
              ),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                Value<String> schoolId = const Value.absent(),
                Value<String> fullName = const Value.absent(),
                Value<String?> positionTitle = const Value.absent(),
                Value<String> personnelType = const Value.absent(),
                Value<String?> email = const Value.absent(),
                Value<String?> mobileNo = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => SchoolPersonnelTableCompanion(
                id: id,
                serverId: serverId,
                schoolId: schoolId,
                fullName: fullName,
                positionTitle: positionTitle,
                personnelType: personnelType,
                email: email,
                mobileNo: mobileNo,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<int?> serverId = const Value.absent(),
                required String schoolId,
                required String fullName,
                Value<String?> positionTitle = const Value.absent(),
                Value<String> personnelType = const Value.absent(),
                Value<String?> email = const Value.absent(),
                Value<String?> mobileNo = const Value.absent(),
                Value<DateTime> updatedAt = const Value.absent(),
                Value<bool> isDirty = const Value.absent(),
                Value<bool> isDeleted = const Value.absent(),
              }) => SchoolPersonnelTableCompanion.insert(
                id: id,
                serverId: serverId,
                schoolId: schoolId,
                fullName: fullName,
                positionTitle: positionTitle,
                personnelType: personnelType,
                email: email,
                mobileNo: mobileNo,
                updatedAt: updatedAt,
                isDirty: isDirty,
                isDeleted: isDeleted,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$SchoolPersonnelTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $SchoolPersonnelTableTable,
      SchoolPersonnelRow,
      $$SchoolPersonnelTableTableFilterComposer,
      $$SchoolPersonnelTableTableOrderingComposer,
      $$SchoolPersonnelTableTableAnnotationComposer,
      $$SchoolPersonnelTableTableCreateCompanionBuilder,
      $$SchoolPersonnelTableTableUpdateCompanionBuilder,
      (
        SchoolPersonnelRow,
        BaseReferences<
          _$AppDatabase,
          $SchoolPersonnelTableTable,
          SchoolPersonnelRow
        >,
      ),
      SchoolPersonnelRow,
      PrefetchHooks Function()
    >;
typedef $$SyncOutboxTableTableCreateCompanionBuilder =
    SyncOutboxTableCompanion Function({
      Value<int> id,
      required String operation,
      required String entity,
      Value<int?> localId,
      required String payload,
      Value<DateTime> createdAt,
      Value<int> attempts,
      Value<bool> failed,
      Value<bool> conflict,
      Value<String?> errorMessage,
    });
typedef $$SyncOutboxTableTableUpdateCompanionBuilder =
    SyncOutboxTableCompanion Function({
      Value<int> id,
      Value<String> operation,
      Value<String> entity,
      Value<int?> localId,
      Value<String> payload,
      Value<DateTime> createdAt,
      Value<int> attempts,
      Value<bool> failed,
      Value<bool> conflict,
      Value<String?> errorMessage,
    });

class $$SyncOutboxTableTableFilterComposer
    extends Composer<_$AppDatabase, $SyncOutboxTableTable> {
  $$SyncOutboxTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get operation => $composableBuilder(
    column: $table.operation,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get entity => $composableBuilder(
    column: $table.entity,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<int> get localId => $composableBuilder(
    column: $table.localId,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get payload => $composableBuilder(
    column: $table.payload,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get createdAt => $composableBuilder(
    column: $table.createdAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<int> get attempts => $composableBuilder(
    column: $table.attempts,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get failed => $composableBuilder(
    column: $table.failed,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<bool> get conflict => $composableBuilder(
    column: $table.conflict,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get errorMessage => $composableBuilder(
    column: $table.errorMessage,
    builder: (column) => ColumnFilters(column),
  );
}

class $$SyncOutboxTableTableOrderingComposer
    extends Composer<_$AppDatabase, $SyncOutboxTableTable> {
  $$SyncOutboxTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<int> get id => $composableBuilder(
    column: $table.id,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get operation => $composableBuilder(
    column: $table.operation,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get entity => $composableBuilder(
    column: $table.entity,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<int> get localId => $composableBuilder(
    column: $table.localId,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get payload => $composableBuilder(
    column: $table.payload,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get createdAt => $composableBuilder(
    column: $table.createdAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<int> get attempts => $composableBuilder(
    column: $table.attempts,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get failed => $composableBuilder(
    column: $table.failed,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<bool> get conflict => $composableBuilder(
    column: $table.conflict,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get errorMessage => $composableBuilder(
    column: $table.errorMessage,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$SyncOutboxTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $SyncOutboxTableTable> {
  $$SyncOutboxTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<int> get id =>
      $composableBuilder(column: $table.id, builder: (column) => column);

  GeneratedColumn<String> get operation =>
      $composableBuilder(column: $table.operation, builder: (column) => column);

  GeneratedColumn<String> get entity =>
      $composableBuilder(column: $table.entity, builder: (column) => column);

  GeneratedColumn<int> get localId =>
      $composableBuilder(column: $table.localId, builder: (column) => column);

  GeneratedColumn<String> get payload =>
      $composableBuilder(column: $table.payload, builder: (column) => column);

  GeneratedColumn<DateTime> get createdAt =>
      $composableBuilder(column: $table.createdAt, builder: (column) => column);

  GeneratedColumn<int> get attempts =>
      $composableBuilder(column: $table.attempts, builder: (column) => column);

  GeneratedColumn<bool> get failed =>
      $composableBuilder(column: $table.failed, builder: (column) => column);

  GeneratedColumn<bool> get conflict =>
      $composableBuilder(column: $table.conflict, builder: (column) => column);

  GeneratedColumn<String> get errorMessage => $composableBuilder(
    column: $table.errorMessage,
    builder: (column) => column,
  );
}

class $$SyncOutboxTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $SyncOutboxTableTable,
          SyncOutboxRow,
          $$SyncOutboxTableTableFilterComposer,
          $$SyncOutboxTableTableOrderingComposer,
          $$SyncOutboxTableTableAnnotationComposer,
          $$SyncOutboxTableTableCreateCompanionBuilder,
          $$SyncOutboxTableTableUpdateCompanionBuilder,
          (
            SyncOutboxRow,
            BaseReferences<_$AppDatabase, $SyncOutboxTableTable, SyncOutboxRow>,
          ),
          SyncOutboxRow,
          PrefetchHooks Function()
        > {
  $$SyncOutboxTableTableTableManager(
    _$AppDatabase db,
    $SyncOutboxTableTable table,
  ) : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$SyncOutboxTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$SyncOutboxTableTableOrderingComposer($db: db, $table: table),
          createComputedFieldComposer: () =>
              $$SyncOutboxTableTableAnnotationComposer($db: db, $table: table),
          updateCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                Value<String> operation = const Value.absent(),
                Value<String> entity = const Value.absent(),
                Value<int?> localId = const Value.absent(),
                Value<String> payload = const Value.absent(),
                Value<DateTime> createdAt = const Value.absent(),
                Value<int> attempts = const Value.absent(),
                Value<bool> failed = const Value.absent(),
                Value<bool> conflict = const Value.absent(),
                Value<String?> errorMessage = const Value.absent(),
              }) => SyncOutboxTableCompanion(
                id: id,
                operation: operation,
                entity: entity,
                localId: localId,
                payload: payload,
                createdAt: createdAt,
                attempts: attempts,
                failed: failed,
                conflict: conflict,
                errorMessage: errorMessage,
              ),
          createCompanionCallback:
              ({
                Value<int> id = const Value.absent(),
                required String operation,
                required String entity,
                Value<int?> localId = const Value.absent(),
                required String payload,
                Value<DateTime> createdAt = const Value.absent(),
                Value<int> attempts = const Value.absent(),
                Value<bool> failed = const Value.absent(),
                Value<bool> conflict = const Value.absent(),
                Value<String?> errorMessage = const Value.absent(),
              }) => SyncOutboxTableCompanion.insert(
                id: id,
                operation: operation,
                entity: entity,
                localId: localId,
                payload: payload,
                createdAt: createdAt,
                attempts: attempts,
                failed: failed,
                conflict: conflict,
                errorMessage: errorMessage,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$SyncOutboxTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $SyncOutboxTableTable,
      SyncOutboxRow,
      $$SyncOutboxTableTableFilterComposer,
      $$SyncOutboxTableTableOrderingComposer,
      $$SyncOutboxTableTableAnnotationComposer,
      $$SyncOutboxTableTableCreateCompanionBuilder,
      $$SyncOutboxTableTableUpdateCompanionBuilder,
      (
        SyncOutboxRow,
        BaseReferences<_$AppDatabase, $SyncOutboxTableTable, SyncOutboxRow>,
      ),
      SyncOutboxRow,
      PrefetchHooks Function()
    >;
typedef $$SyncMetadataTableTableCreateCompanionBuilder =
    SyncMetadataTableCompanion Function({
      required String tableKey,
      Value<DateTime?> lastSyncedAt,
      Value<String?> etag,
      Value<int> rowid,
    });
typedef $$SyncMetadataTableTableUpdateCompanionBuilder =
    SyncMetadataTableCompanion Function({
      Value<String> tableKey,
      Value<DateTime?> lastSyncedAt,
      Value<String?> etag,
      Value<int> rowid,
    });

class $$SyncMetadataTableTableFilterComposer
    extends Composer<_$AppDatabase, $SyncMetadataTableTable> {
  $$SyncMetadataTableTableFilterComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnFilters<String> get tableKey => $composableBuilder(
    column: $table.tableKey,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<DateTime> get lastSyncedAt => $composableBuilder(
    column: $table.lastSyncedAt,
    builder: (column) => ColumnFilters(column),
  );

  ColumnFilters<String> get etag => $composableBuilder(
    column: $table.etag,
    builder: (column) => ColumnFilters(column),
  );
}

class $$SyncMetadataTableTableOrderingComposer
    extends Composer<_$AppDatabase, $SyncMetadataTableTable> {
  $$SyncMetadataTableTableOrderingComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  ColumnOrderings<String> get tableKey => $composableBuilder(
    column: $table.tableKey,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<DateTime> get lastSyncedAt => $composableBuilder(
    column: $table.lastSyncedAt,
    builder: (column) => ColumnOrderings(column),
  );

  ColumnOrderings<String> get etag => $composableBuilder(
    column: $table.etag,
    builder: (column) => ColumnOrderings(column),
  );
}

class $$SyncMetadataTableTableAnnotationComposer
    extends Composer<_$AppDatabase, $SyncMetadataTableTable> {
  $$SyncMetadataTableTableAnnotationComposer({
    required super.$db,
    required super.$table,
    super.joinBuilder,
    super.$addJoinBuilderToRootComposer,
    super.$removeJoinBuilderFromRootComposer,
  });
  GeneratedColumn<String> get tableKey =>
      $composableBuilder(column: $table.tableKey, builder: (column) => column);

  GeneratedColumn<DateTime> get lastSyncedAt => $composableBuilder(
    column: $table.lastSyncedAt,
    builder: (column) => column,
  );

  GeneratedColumn<String> get etag =>
      $composableBuilder(column: $table.etag, builder: (column) => column);
}

class $$SyncMetadataTableTableTableManager
    extends
        RootTableManager<
          _$AppDatabase,
          $SyncMetadataTableTable,
          SyncMetadataRow,
          $$SyncMetadataTableTableFilterComposer,
          $$SyncMetadataTableTableOrderingComposer,
          $$SyncMetadataTableTableAnnotationComposer,
          $$SyncMetadataTableTableCreateCompanionBuilder,
          $$SyncMetadataTableTableUpdateCompanionBuilder,
          (
            SyncMetadataRow,
            BaseReferences<
              _$AppDatabase,
              $SyncMetadataTableTable,
              SyncMetadataRow
            >,
          ),
          SyncMetadataRow,
          PrefetchHooks Function()
        > {
  $$SyncMetadataTableTableTableManager(
    _$AppDatabase db,
    $SyncMetadataTableTable table,
  ) : super(
        TableManagerState(
          db: db,
          table: table,
          createFilteringComposer: () =>
              $$SyncMetadataTableTableFilterComposer($db: db, $table: table),
          createOrderingComposer: () =>
              $$SyncMetadataTableTableOrderingComposer($db: db, $table: table),
          createComputedFieldComposer: () =>
              $$SyncMetadataTableTableAnnotationComposer(
                $db: db,
                $table: table,
              ),
          updateCompanionCallback:
              ({
                Value<String> tableKey = const Value.absent(),
                Value<DateTime?> lastSyncedAt = const Value.absent(),
                Value<String?> etag = const Value.absent(),
                Value<int> rowid = const Value.absent(),
              }) => SyncMetadataTableCompanion(
                tableKey: tableKey,
                lastSyncedAt: lastSyncedAt,
                etag: etag,
                rowid: rowid,
              ),
          createCompanionCallback:
              ({
                required String tableKey,
                Value<DateTime?> lastSyncedAt = const Value.absent(),
                Value<String?> etag = const Value.absent(),
                Value<int> rowid = const Value.absent(),
              }) => SyncMetadataTableCompanion.insert(
                tableKey: tableKey,
                lastSyncedAt: lastSyncedAt,
                etag: etag,
                rowid: rowid,
              ),
          withReferenceMapper: (p0) => p0
              .map((e) => (e.readTable(table), BaseReferences(db, table, e)))
              .toList(),
          prefetchHooksCallback: null,
        ),
      );
}

typedef $$SyncMetadataTableTableProcessedTableManager =
    ProcessedTableManager<
      _$AppDatabase,
      $SyncMetadataTableTable,
      SyncMetadataRow,
      $$SyncMetadataTableTableFilterComposer,
      $$SyncMetadataTableTableOrderingComposer,
      $$SyncMetadataTableTableAnnotationComposer,
      $$SyncMetadataTableTableCreateCompanionBuilder,
      $$SyncMetadataTableTableUpdateCompanionBuilder,
      (
        SyncMetadataRow,
        BaseReferences<_$AppDatabase, $SyncMetadataTableTable, SyncMetadataRow>,
      ),
      SyncMetadataRow,
      PrefetchHooks Function()
    >;

class $AppDatabaseManager {
  final _$AppDatabase _db;
  $AppDatabaseManager(this._db);
  $$UserProfileTableTableTableManager get userProfileTable =>
      $$UserProfileTableTableTableManager(_db, _db.userProfileTable);
  $$MemoTableTableTableManager get memoTable =>
      $$MemoTableTableTableManager(_db, _db.memoTable);
  $$AccomplishmentTableTableTableManager get accomplishmentTable =>
      $$AccomplishmentTableTableTableManager(_db, _db.accomplishmentTable);
  $$SchoolTableTableTableManager get schoolTable =>
      $$SchoolTableTableTableManager(_db, _db.schoolTable);
  $$SchoolPersonnelTableTableTableManager get schoolPersonnelTable =>
      $$SchoolPersonnelTableTableTableManager(_db, _db.schoolPersonnelTable);
  $$SyncOutboxTableTableTableManager get syncOutboxTable =>
      $$SyncOutboxTableTableTableManager(_db, _db.syncOutboxTable);
  $$SyncMetadataTableTableTableManager get syncMetadataTable =>
      $$SyncMetadataTableTableTableManager(_db, _db.syncMetadataTable);
}
